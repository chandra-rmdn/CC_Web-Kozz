<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Koszzz</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Animasi untuk upload */
        @keyframes pulse {
            0% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.6;
            }
        }

        .uploading {
            animation: pulse 1.5s infinite;
        }

        /* Style untuk drag & drop aktif */
        .drag-active {
            border-color: #5C00CC !important;
            background-color: rgba(92, 0, 204, 0.05) !important;
        }
    </style>
</head>

<body class="bg-[#F6F5FB]">
    <!-- Navbar -->
    <header class="w-full flex items-center justify-between px-4 md:px-[88px] py-[14px] bg-[#F6F5FB]">
        <button class="flex items-center gap-[7px]" onclick="window.location.href='/'">
            <div class="icon-home text-black"><i class="bi bi-houses-fill text-2xl md:text-[34px]"></i></div>
            <span class="text-2xl md:text-[32px] font-poppins font-bold text-[#5C00CC]">Koszzz</span>
        </button>
        @auth
            <div class="flex items-center gap-[12px]">
                <a href="{{ route('user.profile') }}">
                    <button class="btn btn-circle shadow-none" style="--btn-color:#DCDCDC">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </button>
                </a>

                <div class="dropdown relative inline-flex">
                    <button id="dropdown-menu-icon" type="button" class="dropdown-toggle btn btn-circle shadow-none"
                        style="--btn-color:#DCDCDC" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                        <i class="text-black bi bi-list"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-open:opacity-100 bg-white shadow-md hidden min-w-50" role="menu"
                        aria-orientation="vertical" aria-labelledby="dropdown-menu-icon">
                        <li><a class="dropdown-item text-black gap-1 text-[15px]" href="{{ route('user.profile') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Profile
                            </a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-black gap-1 text-[15px] w-full text-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </header>

    <!-- Back -->
    <main class="px-2 md:px-[61px] pt-[25px]">
        <!-- Card utama -->
        <section class="bg-white rounded-t-[20px] shadow-sm px-4 md:px-8 py-6 md:pt-[38px] max-w-4xl">
            <a href="/detail_kos">
                <button class="flex items-center gap-1 text-xs text-gray-600 mb-4">
                    <span>&lt;</span>
                    <span>Kembali</span>
                </button>
            </a>

            <div class="md:px-[62px] md:pt-1">
                <!-- Progress step -->
                <h1 class="text-lg text-black md:text-xl font-semibold mb-6">Lengkapi Data Kos</h1>
                <div class="flex items-center gap-6 text-[11px] text-gray-500 mb-6">
                    <div class="flex flex-col items-center gap-1">
                        <span class="w-13 h-[6px] rounded-full bg-[#5C00CC]"></span>
                        <span>Ajukan sewa</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="w-13 h-[6px] rounded-full bg-gray-200"></span>
                        <span>Pemilik setuju</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="w-13 h-[6px] rounded-full bg-gray-200"></span>
                        <span>Bayar sewa</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="w-13 h-[6px] rounded-full bg-gray-200"></span>
                        <span>Check-in</span>
                    </div>
                </div>
                <!-- Informasi penyewa -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm text-black font-semibold">Informasi penyewa</h2>
                </div>
                <div class="space-y-2 text-xs text-gray-700 mb-6">
                    <div>
                        <p class="font-medium text-black mb-1">Nama penyewa</p>
                        <p class="text-gray-500 mb-3">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-black mb-1">Nomor HP</p>
                        <p class="text-gray-500 mb-3">{{ auth()->user()->no_hp }}</p>
                    </div>
                    <div class="flex flex-wrap gap-6">
                        <div>
                            <p class="font-medium text-black mb-1">Jenis kelamin</p>
                            <p class="text-gray-500">
                                @php
                                    $jk = auth()->user()->jenis_kelamin;
                                    if ($jk == 'L') {
                                        echo 'Laki-laki';
                                    } elseif ($jk == 'P') {
                                        echo 'Perempuan';
                                    } elseif (empty($jk)) {
                                        echo '-';
                                    } else {
                                        echo $jk;
                                    }
                                @endphp
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-6" />

                <!-- Durasi ngekos -->
                <h2 class="text-sm text-black font-semibold mb-3">Durasi ngekos</h2>
                <div class="max-w-32 mb-6" data-input-number='{ "max": 12, "min": 0 }'>
                    <label class="label-text text-gray-500 text-xs" for="number-input-mini">Bulan:</label>
                    <div class="input items-center bg-white border-gray-300 h-7">
                        <button type="button" class="btn btn-soft bg-white size-5 min-h-0 rounded-sm p-0"
                            aria-label="Decrement button" id="btn-decrement" data-input-number-decrement>
                            <span class="icon-[tabler--minus] size-5 shrink-0"></span>
                        </button>
                        <input class="text-center text-gray-500" type="number" value="{{ $durasiAwal }}"
                            min="{{ $minDurasi }}" max="{{ $maxDurasi }}" step="{{ $kelipatan }}"
                            aria-label="Mini stacked buttons" data-input-number-input id="number-input-mini" />
                        <button type="button" class="btn btn-soft bg-white size-5 min-h-0 rounded-sm p-0"
                            aria-label="Increment button" id="btn-increment" data-input-number-increment>
                            <span class="icon-[tabler--plus] size-5 shrink-0"></span>
                        </button>
                    </div>
                </div>

                <!-- Tanggal mulai -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-sm text-black font-semibold mb-3">Tanggal mulai ngekos</h2>
                        <p class="text-xs text-gray-500" id="displayDate">
                            {{ \Carbon\Carbon::parse($tanggal_mulai)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                    </div>
                    <div>
                        <input type="text" id="dateInput" class="absolute opacity-0 pointer-events-none w-2 h-4 mt-4" />
                        <button class="text-xs text-[#5C00CC] link link-animated [--link-color:purple]"
                            id="btnDate">Ubah</button>
                    </div>
                    <!-- <input type="hidden" name="tanggal_mulai_final" id="tanggal-mulai-final" value="{{ $tanggal_mulai }}"> -->
                </div>

                <!-- Deskripsi kos -->
                <div class="mb-6">
                    <h2 class="text-sm text-black font-semibold mb-2">Catatan tambahan</h2>
                    <div class="me-0 md:me-80">
                        <textarea class="w-full h-18 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-500 outline-none focus:ring-1 focus:ring-[#5C00CC] resize-none" 
                                placeholder="Tulis detail tambahan di sini" name="catatan_penyewa"></textarea>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-6" />

                <!-- Biaya sewa -->
                <div class="mb-8">
                    <h2 class="text-sm text-black font-semibold mb-2">Biaya sewa kos</h2>
                    <div class="flex items-center justify-between text-xs">
                        <p class="text-gray-500">Harga sewa per {{ $labelSatuan }}</p>
                        <p class="text-sm text-black font-semibold">Rp
                            {{ number_format($hargaSatuan, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-black font-semibold">Total Harga</span>
                        <span class="text-lg font-bold text-[#5C00CC]" id="total-harga-display">
                            Rp {{ number_format($harga_total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(this)">
                    @csrf
                    <input type="hidden" name="kos_id" value="{{ $kos->id }}">
                    <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                    <input type="hidden" name="tanggal_mulai_final" id="tanggal-mulai-final"
                        value="{{ $tanggal_mulai }}">
                    <input type="hidden" name="periode_final" id="periode-final" value="{{ $periode }}">
                    <input type="hidden" name="harga_total_final" id="harga-total-final" value="{{ $harga_total }}">
                    <input type="hidden" name="durasi_final" id="durasi-final" value="{{ $durasiAwal }}">
                    <input type="file" name="ktp_file" id="ktp-file-input" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                    <input type="hidden" name="catatan_penyewa" id="catatan-penyewa-final">
                    <!-- Button utama -->
                    <button type="submit"
                        class="btn md:w-40 h-9 font-medium text-white text-xs font-medium rounded-full w-full bg-[#5C00CC] mt-4">Ajukan
                        Sewa</button>
                </form>
            </div>
        </section>
    </main>
    <script src="{{ asset('js/flyonui.js') }}"></script>
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script src="{{ asset('js/id.js') }}"></script>
    <script>
        function handleCheckoutSubmit(form) {
            console.log('🚀 Checkout form submit handler');
            
            // 1. Update catatan dari textarea ke hidden input
            const catatanTextarea = document.querySelector('textarea[name="catatan_penyewa"]');
            const catatanInput = document.getElementById('catatan-penyewa-final');
            
            if (catatanTextarea && catatanInput) {
                const catatanValue = catatanTextarea.value.trim();
                catatanInput.value = catatanValue;
                console.log('📝 Catatan penyewa:', catatanValue);
            }
            
            // 2. Update durasi (from existing JavaScript)
            const numberInput = document.getElementById('number-input-mini');
            const durasiFinal = document.getElementById('durasi-final');
            if (numberInput && durasiFinal) {
                durasiFinal.value = numberInput.value;
                console.log('📊 Durasi updated:', numberInput.value);
            }
            
            // 3. Validasi tanggal
            const tanggal = document.getElementById('tanggal-mulai-final')?.value;
            if (!tanggal) {
                alert('Pilih tanggal mulai sewa!');
                return false;
            }
            
            // 4. Optional: Tampilkan konfirmasi
            // alert('Booking akan diajukan dengan catatan: ' + (catatanValue || '(tidak ada catatan)'));
            
            return true; // Lanjutkan submit
        }

        // ✅ Sync real-time untuk catatan (optional improvement)
        document.addEventListener('DOMContentLoaded', function() {
            const catatanTextarea = document.querySelector('textarea[name="catatan_penyewa"]');
            const catatanInput = document.getElementById('catatan-penyewa-final');
            
            if (catatanTextarea && catatanInput) {
                // Sync real-time saat user mengetik
                catatanTextarea.addEventListener('input', function() {
                    catatanInput.value = this.value.trim();
                });
                
                // Set initial value
                catatanInput.value = catatanTextarea.value.trim();
                console.log('📝 Catatan initial sync:', catatanInput.value);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== BAGIAN 1: FLATPICKR UNTUK TANGGAL ==========
            const btnDate = document.getElementById('btnDate');
            const displayDate = document.getElementById('displayDate');
            const tanggalAwal = "{{ $tanggal_mulai }}";
            const hiddenInput = document.getElementById('tanggal-mulai-final');

            if (hiddenInput) {
                hiddenInput.addEventListener('change', function () {
                    console.log('📅 Hidden input changed to:', this.value);
                    const debugEl = document.getElementById('debug-tanggal');
                    if (debugEl) debugEl.textContent = this.value;
                });
            }

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
                        const tanggalValue = `${yyyy}-${mm}-${dd}`;
                        document.querySelectorAll('input[name="tanggal_mulai_final"]').forEach(input => {
                            input.value = tanggalValue;
                        });
                        console.log('✅ Tanggal updated to:', tanggalValue);
                        if (hiddenInput) {
                            hiddenInput.dispatchEvent(new Event('change'));
                        }
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
                        if (durasi > minDurasi) {
                            durasi = Math.max(durasi - kelipatan, minDurasi);
                            numberInput.value = durasi;
                            updateHarga();
                        }
                    });

                    btnPlus.addEventListener('click', function (e) {
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
                    e.stopPropagation(); // ⬅️ TAMBAH INI
                    e.preventDefault();  // ⬅️ TAMBAH INI
                }
            }, true);

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

            // const form = document.querySelector('form');
            // if (form) {
            //     form.addEventListener('submit', function (e) {
            //         // Validasi
            //         const tanggal = document.getElementById('tanggal-mulai-final')?.value;
            //         if (!tanggal) {
            //             e.preventDefault();
            //             alert('Pilih tanggal mulai sewa!');
            //             return false;
            //         }

            //         // Force update durasi dari input number
            //         const numberInput = document.getElementById('number-input-mini');
            //         const durasiFinal = document.getElementById('durasi-final');
            //         if (numberInput && durasiFinal) {
            //             durasiFinal.value = numberInput.value;
            //             console.log('📝 Durasi updated to:', numberInput.value);
            //         }

            //         const catatanTextarea = document.querySelector('textarea[name="catatan_penyewa"]');
            //         const catatanInput = document.getElementById('catatan-penyewa-final');
                    
            //         if (catatanTextarea && catatanInput) {
            //             catatanInput.value = catatanTextarea.value.trim();
            //             console.log('📝 Catatan dikirim:', catatanInput.value);
                        
            //             // ✅ DEBUG: Tampilkan alert untuk konfirmasi
            //             alert('Catatan akan dikirim: "' + catatanInput.value + '"');
            //         } else {
            //             console.error('❌ Elemen catatan tidak ditemukan!');
            //             alert('ERROR: Elemen catatan tidak ditemukan!');
            //         }
            //     });
            // }
        });
    </script>
</body>

</html>