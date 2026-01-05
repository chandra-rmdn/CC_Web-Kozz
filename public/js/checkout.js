document.addEventListener('DOMContentLoaded', function () {
            // ========== BAGIAN 1: FLATPICKR UNTUK TANGGAL ==========
            const btnDate = document.getElementById('btnDate');
            const displayDate = document.getElementById('displayDate');
            const tanggalAwal = "{{ $tanggal_mulai }}";

            // CLEAN tanggal dulu
            const cleanDate = tanggalAwal.trim();

            // Coba parse ke Date object
            let dateObj;
            try {
                // Jika format Y-m-d (2025-12-31)
                if (cleanDate.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    const parts = cleanDate.split('-');
                    dateObj = new Date(parts[0], parts[1] - 1, parts[2]); // Month is 0-based
                } else {
                    // Coba parse sebagai string biasa
                    dateObj = new Date(cleanDate);
                }
            } catch (e) {
                dateObj = new Date(); // Fallback ke hari ini
            }

            // INIT FLATPICKR
            const fp = flatpickr("#dateInput", {
                locale: "id",
                dateFormat: "l, j F Y",
                minDate: "today",
                disableMobile: "true",
                onChange: function (selectedDates, dateStr) {
                    displayDate.textContent = dateStr;

                    // Update hidden input dengan format Y-m-d
                    if (selectedDates[0]) {
                        const yyyy = selectedDates[0].getFullYear();
                        const mm = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                        const dd = String(selectedDates[0].getDate()).padStart(2, '0');
                        document.getElementById('tanggal-mulai-final').value = `${yyyy}-${mm}-${dd}`;
                    }
                }
            });

            // SET DATE MANUAL SETELAH INIT
            setTimeout(() => {
                try {
                    if (!isNaN(dateObj.getTime())) {
                        fp.setDate(dateObj, false);

                        // Juga update displayDate dengan tanggal awal
                        const formatted = fp.formatDate(dateObj, "l, j F Y");
                        displayDate.textContent = formatted;
                    } else {
                        fp.setDate(new Date(), false);
                    }
                } catch (e) {
                    console.error('❌ Error setting date:', e);
                }
            }, 100);

            // Event untuk button "Ubah"
            btnDate.addEventListener('click', function (e) {
                e.preventDefault();
                fp.open();
            });

            // ========== BAGIAN 2: DURASI DAN HARGA ==========
            // Data dari PHP
            const hargaSatuan = parseFloat("{{ $hargaSatuan ?? 0 }}");
            const kelipatan = parseInt("{{ $kelipatan ?? 1 }}");
            const durasiAwal = parseInt("{{ $durasiAwal ?? 1 }}");
            const maxDurasi = parseInt("{{ $maxDurasi ?? 12 }}");
            const minDurasi = kelipatan;

            // Elemen
            const numberInput = document.getElementById('number-input-mini');
            const totalDisplay = document.getElementById('total-harga-display');
            const hargaHidden = document.getElementById('harga-total-final');
            const durasiHidden = document.getElementById('durasi-final');

            let durasi = durasiAwal;

            // Setup input durasi
            if (numberInput) {
                numberInput.value = durasi;
                numberInput.min = minDurasi;
                numberInput.max = maxDurasi;
                numberInput.step = kelipatan;

                // Replace tombol dengan versi kita
                const container = numberInput.closest('.input');
                if (container) {
                    // Hapus tombol lama
                    const oldButtons = container.querySelectorAll('button');
                    oldButtons.forEach(btn => btn.remove());

                    // Buat tombol baru
                    const btnMinus = document.createElement('button');
                    btnMinus.innerHTML = '<span class="icon-[tabler--minus] size-5 shrink-0"></span>';
                    btnMinus.className = 'btn btn-soft bg-white size-5 min-h-0 rounded-sm p-0';
                    btnMinus.setAttribute('aria-label', 'Decrement button');

                    const btnPlus = document.createElement('button');
                    btnPlus.innerHTML = '<span class="icon-[tabler--plus] size-5 shrink-0"></span>';
                    btnPlus.className = 'btn btn-soft bg-white size-5 min-h-0 rounded-sm p-0';
                    btnPlus.setAttribute('aria-label', 'Increment button');

                    // Insert tombol
                    container.insertBefore(btnMinus, numberInput);
                    container.appendChild(btnPlus);

                    // Event untuk tombol baru
                    btnMinus.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (durasi > minDurasi) {
                            durasi = Math.max(durasi - kelipatan, minDurasi);
                            numberInput.value = durasi;
                            updateHarga();
                        }
                    });

                    btnPlus.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (durasi < maxDurasi) {
                            durasi = Math.min(durasi + kelipatan, maxDurasi);
                            numberInput.value = durasi;
                            updateHarga();
                        }
                    });
                }

                // Event input
                numberInput.addEventListener('change', function () {
                    let val = parseInt(this.value) || minDurasi;
                    if (val % kelipatan !== 0) {
                        val = Math.round(val / kelipatan) * kelipatan;
                    }
                    val = Math.max(minDurasi, Math.min(val, maxDurasi));
                    this.value = val;
                    durasi = val;
                    updateHarga();
                });
            }

            function updateHarga() {
                const multiplier = durasi / kelipatan;
                const total = Math.round(hargaSatuan * multiplier);

                if (totalDisplay) {
                    totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
                }
                if (hargaHidden) hargaHidden.value = total;
                if (durasiHidden) durasiHidden.value = durasi;

                updateButtonState();
            }

            function updateButtonState() {
                const btnMinus = document.querySelector('[aria-label="Decrement button"]');
                const btnPlus = document.querySelector('[aria-label="Increment button"]');

                if (btnMinus) {
                    btnMinus.disabled = durasi <= minDurasi;
                    btnMinus.style.opacity = durasi <= minDurasi ? '0.5' : '1';
                }
                if (btnPlus) {
                    btnPlus.disabled = durasi >= maxDurasi;
                    btnPlus.style.opacity = durasi >= maxDurasi ? '0.5' : '1';
                }
            }

            // Initialize harga
            updateHarga();

            // ========== BAGIAN 3: UPLOAD FILE KTP ==========
            const fileInput = document.getElementById('fileInput'); // <-- PERHATIAN INI!
            const uploadLabel = document.getElementById('uploadLabel');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const fileIcon = document.getElementById('fileIcon');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const progressBar = document.getElementById('progressBar');
            const removeFile = document.getElementById('removeFile');
            const errorMessage = document.getElementById('errorMessage');

            // KTP file input di form
            const ktpFileInput = document.getElementById('ktp-file-input'); // <-- INI YANG UNTUK FORM!

            // Max file size: 2MB
            const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB in bytes

            // Allowed file types
            const ALLOWED_TYPES = {
                'image/jpeg': 'jpg',
                'image/jpg': 'jpg',
                'image/png': 'png',
                'application/pdf': 'pdf'
            };

            // Current file data
            let currentFile = null;

            // Event: Click upload area
            uploadLabel.addEventListener('click', function (e) {
                if (!currentFile) {
                    fileInput.click();
                }
            });

            // Event: File selected
            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];

                if (file) {
                    validateAndPreviewFile(file);
                }
            });

            // Event: Drag and drop
            uploadLabel.addEventListener('dragover', function (e) {
                e.preventDefault();
                uploadLabel.classList.add('border-[#5C00CC]', 'bg-purple-50');
            });

            uploadLabel.addEventListener('dragleave', function (e) {
                e.preventDefault();
                uploadLabel.classList.remove('border-[#5C00CC]', 'bg-purple-50');
            });

            uploadLabel.addEventListener('drop', function (e) {
                e.preventDefault();
                uploadLabel.classList.remove('border-[#5C00CC]', 'bg-purple-50');

                const file = e.dataTransfer.files[0];
                if (file) {
                    validateAndPreviewFile(file);
                }
            });

            // Event: Remove file
            removeFile.addEventListener('click', function () {
                resetUpload();
            });

            // Function to validate and preview file
            function validateAndPreviewFile(file) {
                // Reset error
                hideError();

                // Validate file size
                if (file.size > MAX_FILE_SIZE) {
                    showError('File terlalu besar. Maksimal 2MB.');
                    return;
                }

                // Validate file type
                if (!ALLOWED_TYPES[file.type]) {
                    showError('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                    return;
                }

                // Store file
                currentFile = file;

                // Show preview
                showPreview(file);

                // Set file ke form input ktp_file
                setFileToKtpInput(file);

                // Simulate upload progress (for demo)
                simulateUploadProgress();
            }

            // Function to show preview
            function showPreview(file) {
                // Update file info
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                // Show image preview for image files
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        fileIcon.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    // For PDF, show icon
                    imagePreview.classList.add('hidden');
                    fileIcon.classList.remove('hidden');
                    fileIcon.className = 'bi bi-file-earmark-pdf text-red-500';
                }

                // Show preview container
                previewContainer.classList.remove('hidden');
                uploadLabel.classList.add('hidden');
            }

            // Function to set file to ktp_file input in form
            function setFileToKtpInput(file) {
                if (!ktpFileInput) {
                    console.error('❌ ktp-file-input not found!');
                    return;
                }

                // Create a new DataTransfer object
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                // Assign the file to the input element
                ktpFileInput.files = dataTransfer.files;

                console.log('✅ File set to ktp_file input:', file.name);
            }

            // Function to simulate upload progress
            function simulateUploadProgress() {
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    progressBar.style.width = progress + '%';

                    if (progress >= 100) {
                        clearInterval(interval);
                        progressBar.classList.remove('bg-green-500');
                        progressBar.classList.add('bg-green-400');
                    }
                }, 50);
            }

            // Function to reset upload
            function resetUpload() {
                currentFile = null;
                fileInput.value = '';

                // Clear ktp_file input
                if (ktpFileInput) {
                    ktpFileInput.value = '';
                }

                // Hide preview
                previewContainer.classList.add('hidden');
                uploadLabel.classList.remove('hidden');

                // Reset preview elements
                imagePreview.classList.add('hidden');
                fileIcon.classList.remove('hidden');
                fileIcon.className = 'bi bi-file-earmark-text text-gray-400';

                // Reset progress bar
                progressBar.style.width = '0%';
                progressBar.classList.remove('bg-green-400');
                progressBar.classList.add('bg-green-500');

                hideError();
            }

            // Function to show error
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.classList.remove('hidden');
                uploadLabel.classList.add('border-red-300');
            }

            // Function to hide error
            function hideError() {
                errorMessage.classList.add('hidden');
                uploadLabel.classList.remove('border-red-300');
            }

            // Helper: Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });