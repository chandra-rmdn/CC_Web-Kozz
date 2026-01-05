<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Koszzz - Temukan kos favoritmu</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="{{ asset('css/raty.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.clientKey') }}">
        </script>
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* resources/css/app.css atau di <style> tag */
        .overlay.hidden {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            z-index: -9999 !important;
        }

        .overlay.hidden::before {
            display: none !important;
        }

        /* Force remove backdrop */
        body:not(:has(.overlay:not(.hidden))) {
            overflow: auto !important;
        }

        /* Modal Konfirmasi Ulasan */
        @keyframes checkmark {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        #confirm-modal .modal-content svg {
            animation: checkmark 0.5s ease-out;
        }

        /* Efek hover untuk tombol tutup */
        #confirm-modal .btn-primary:hover {
            background-color: #4a00b3;
            border-color: #4a00b3;
        }
    </style>
    <script>
        function payNow(snapToken, bookingId) {
            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    // Ambil CSRF token dari meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('/update-booking-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            booking_id: bookingId,
                            status: 'selesai',
                            payment_reference: result.order_id
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            window.location.href = "/my_booking#check-in";
                            window.location.reload();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.location.href = "/my_booking#check-in";
                        });
                },
                onPending: function (result) {
                    alert("Pembayaran pending! ID Booking: " + bookingId);
                    window.location.href = "/my_booking#pembayaran";
                },
                onError: function (result) {
                    alert("Pembayaran gagal! Silakan coba lagi. ID Booking: " + bookingId);
                },
                onClose: function () {
                    alert("Anda menutup popup pembayaran tanpa menyelesaikan pembayaran.");
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const tabButtons = document.querySelectorAll(".tab-button");
            const tabContents = document.querySelectorAll(".tab-content");

            tabButtons.forEach(button => {
                button.addEventListener("click", () => {
                    const targetTab = button.getAttribute("data-tab");

                    tabContents.forEach(content => {
                        content.classList.remove("active");
                        if (content.id === targetTab) {
                            content.classList.add("active");
                        }
                    });

                    tabButtons.forEach(btn => btn.classList.remove("active"));
                    button.classList.add("active");
                });
            });
        });

    </script>
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

    <main class="px-2 md:px-[61px] pt-[25px] min-h-[calc(100vh-75px)]">
        <div class="max-w-[350px] h-[50px] px-[20px] mx-auto mb-4
            flex items-center justify-center gap-4 bg-white rounded-[20px] shadow-md overflow-hidden md:hidden">
            <a href="{{ route('user.profile') }}">
                <button class="bg-gray-300 text-white w-36 h-[30px] text-sm font-medium rounded-[12px]">Profil
                    Saya</button>
            </a>
            <a href="{{ route('my.booking') }}">
                <button class="bg-[#5C00CC] text-white w-36 h-[30px] text-sm font-medium rounded-[12px]">Booking
                    Saya</button>
            </a>
        </div>
        <div class="flex gap-12">
            <!-- Sidebar teks -->
            <div class="hidden md:block w-32 pt-8">
                <a href="{{ route('user.profile') }}"
                    class="text-md font-poppins font-semibold text-black mb-6 block">Profil Saya</a>
                <a href="{{ route('my.booking') }}"
                    class="text-md font-poppins font-semibold text-[#5C00CC] mb-3 block">Booking Saya</a>
            </div>

            <!-- Box putih -->
            <section
                class="bg-white flex-1 min-h-[calc(100vh-100px)] rounded-t-[20px] shadow-sm px-4 md:px-8 py-6 md:pt-[38px]">
                <div class="md:px-[58px] md:pt-1">
                    <!-- Title -->
                    <h1 class="text-lg text-black md:text-xl font-semibold mb-8">My Booking Saya</h1>

                    <!-- Tab Contents -->
                    <!-- Tab 1: Menunggu Persetujuan -->
                    <div id="menunggu-persetujuan" class="tab-content active">
                        <p class="text-md text-black md:text-md font-semibold mb-6">Menunggu persetujuan</p>
                        <div class="flex items-center gap-6 text-[11px] text-gray-500 mb-7">
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button active w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="menunggu-persetujuan"></button>
                                <span>Pemilik setuju</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-gray-200"
                                    data-tab="pembayaran"></button>
                                <span>Bayar sewa</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-gray-200"
                                    data-tab="check-in"></button>
                                <span>Check-in</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-gray-200"
                                    data-tab="cancel"></button>
                                <span>Cancel</span>
                            </div>
                        </div>

                        @foreach($menungguBookings as $booking)
                            <!-- Card pesanan -->
                            <div class="border-2 border-gray-200 rounded-[20px] px-6 py-6 mb-6 max-w-xl w-full">
                                <div class="flex justify-between gap-4">
                                    <!-- Left info -->
                                    <div class="space-y-3 text-xs">
                                        <div>
                                            <p class="text-sm text-black font-semibold">
                                                {{ $booking->kos->nama_kos ?? 'Kos' }}
                                            </p>
                                            <span
                                                class="text-black inline-block mt-2 px-2 py-[2px] rounded-full border border-gray-400 text-[10px]">
                                                Kos
                                                {{ $booking->kos->tipe_kos == 'putra' ? 'Putra' : ($booking->kos->tipe_kos == 'putri' ? 'Putri' : 'Campur') }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2 mt-4">
                                            <span class="text-gray-500 w-20">Mulai Sewa:</span>
                                            <span class="font-medium text-black hidden md:inline">
                                                @if($booking->tanggal_checkin)
                                                    {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}
                                                @else
                                                    Belum ditentukan
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Lama Sewa:</span>
                                            @php
                                                $displayDurasi = match ($booking->periode_sewa) {
                                                    'harian' => $booking->durasi_sewa . ' Hari',
                                                    'mingguan' => $booking->durasi_sewa . ' Minggu',
                                                    'bulanan' => $booking->durasi_sewa . ' Bulan',
                                                    '3_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    '6_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    'tahunan' => $booking->durasi_sewa . ' Tahun',
                                                    default => $booking->durasi_sewa . ' Bulan'
                                                };
                                            @endphp

                                            <span class="font-medium text-black">
                                                {{ $displayDurasi }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Durasi Sewa:</span>
                                            @php
                                                $displayPeriode = match ($booking->periode_sewa) {
                                                    'harian' => 'Harian',
                                                    'mingguan' => 'Mingguan',
                                                    'bulanan' => 'Bulanan',
                                                    '3_bulanan' => '3 Bulanan',
                                                    '6_bulanan' => '6 Bulanan',
                                                    'tahunan' => 'Tahunan',
                                                    default => 'Bulanan'
                                                };
                                            @endphp
                                            <span class="font-medium text-black">
                                                {{ $displayPeriode }}
                                            </span>
                                        </div>

                                        <div
                                            class="mt-3 inline-flex px-2 py-1 rounded-[6px] bg-[#FCF7E3] text-[10px] text-[#8C3B00] border-2 border-[#F9E7B4] font-medium">
                                            Menunggu Konfirmasi</div>
                                    </div>

                                    <!-- Right image placeholder -->
                                    <div
                                        class="flex w-30 h-26 rounded-2xl border border-gray-200 items-center justify-center overflow-hidden">
                                        @php
                                            $fotoKos = $booking->kos->fotoKos->where('tipe', 'kamar')->first() ?? null;
                                        @endphp

                                        @if($fotoKos && $fotoKos->path_foto)
                                            @if(str_starts_with($fotoKos->path_foto, '/9j/') || str_starts_with($fotoKos->path_foto, 'data:image'))
                                                <img src="data:image/jpeg;base64,{{ $fotoKos->path_foto }}" alt="Gambar Kos"
                                                    class="w-full h-full object-cover rounded-2xl" />
                                            @endif
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center rounded-2xl">
                                                <i class="bi bi-image text-gray-300 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-center">
                                    <!-- <button 
                                        onclick="confirmCancel({{ $booking->id }}, '{{ $booking->kos->nama_kos ?? 'Kos' }}')"
                                        class="btn w-40 h-9 text-[#FF0000] text-xs font-medium rounded-full bg-white ml-4">
                                        Batalkan</button> -->
                                    <!-- Tambahkan debug -->
                                    <button onclick="console.log('Booking:', {{ $booking }}, 'Status:', '{{ $booking->status }}'); 
                                                    confirmCancel({{ $booking->id }}, '{{ $booking->kos->nama_kos ?? 'Kos' }}', '{{ $booking->status }}')"
                                            class="btn w-40 h-9 text-[#FF0000] text-xs font-medium rounded-full bg-white">
                                        Batalkan
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        @if($menungguBookings->count() == 0)
                            <p class="text-gray-500">Tidak ada booking</p>
                        @endif
                    </div>

                    <!-- Tab 2: Pembayaran -->
                    <div id="pembayaran" class="tab-content">
                        <p class="text-md text-black md:text-md font-semibold mb-6">Pembayaran</p>
                        <div class="flex items-center gap-6 text-[11px] text-gray-500 mb-7">
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button active w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="menunggu-persetujuan"></button>
                                <!-- <span class="w-13 h-[6px] rounded-full bg-[#5C00CC]"></span> -->
                                <span>Pemilik setuju</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="pembayaran"></button>
                                <span>Bayar sewa</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-gray-200"
                                    data-tab="check-in"></button>
                                <span>Check-in</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-gray-200"
                                    data-tab="cancel"></button>
                                <span>Cancel</span>
                            </div>
                        </div>

                        @foreach($pembayaranBookings as $booking)
                            <!-- Card pesanan untuk Pembayaran -->
                            <div class="border-2 border-gray-200 rounded-[20px] px-6 py-6 mb-6 max-w-xl w-full">
                                <div class="flex justify-between gap-4">
                                    <!-- Left info -->
                                    <div class="space-y-3 text-xs">
                                        <div>
                                            <p class="text-sm text-black font-semibold">
                                                {{ $booking->kos->nama_kos ?? 'Kos' }}
                                            </p>
                                            <span
                                                class="text-black inline-block mt-2 px-2 py-[2px] rounded-full border border-gray-400 text-[10px]">
                                                Kos
                                                {{ $booking->kos->tipe_kos == 'putra' ? 'Putra' : ($booking->kos->tipe_kos == 'putri' ? 'Putri' : 'Campur') }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2 mt-4">
                                            <span class="text-gray-500 w-20">Mulai Sewa:</span>
                                            <span class="font-medium text-black hidden md:inline">
                                                @if($booking->tanggal_checkin)
                                                    {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}
                                                @else
                                                    Belum ditentukan
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Lama Sewa:</span>
                                            @php
                                                $displayDurasi = match ($booking->periode_sewa) {
                                                    'harian' => $booking->durasi_sewa . ' Hari',
                                                    'mingguan' => $booking->durasi_sewa . ' Minggu',
                                                    'bulanan' => $booking->durasi_sewa . ' Bulan',
                                                    '3_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    '6_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    'tahunan' => $booking->durasi_sewa . ' Tahun',
                                                    default => $booking->durasi_sewa . ' Bulan'
                                                };
                                            @endphp

                                            <span class="font-medium text-black">
                                                {{ $displayDurasi }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Durasi Sewa:</span>
                                            @php
                                                $displayPeriode = match ($booking->periode_sewa) {
                                                    'harian' => 'Harian',
                                                    'mingguan' => 'Mingguan',
                                                    'bulanan' => 'Bulanan',
                                                    '3_bulanan' => '3 Bulanan',
                                                    '6_bulanan' => '6 Bulanan',
                                                    'tahunan' => 'Tahunan',
                                                    default => 'Bulanan'
                                                };
                                            @endphp
                                            <span class="font-medium text-black">
                                                {{ $displayPeriode }}
                                            </span>
                                        </div>

                                        <span class="text-gray-500 w-20">Total Bayar:</span>
                                        <span class="flex mt-1 font-medium text-[14px] text-black">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </span>

                                        <div
                                            class="inline-flex px-2 py-1 rounded-[6px] bg-blue-100 text-[10px] text-blue-800 border-2 border-blue-200 font-medium">
                                            Menunggu Pembayaran
                                        </div>
                                    </div>

                                    <!-- Right image -->
                                    <div
                                        class="flex w-30 h-26 rounded-2xl border border-gray-200 items-center justify-center">
                                        @php
                                            $fotoKos = $booking->kos->fotoKos->where('tipe', 'kamar')->first() ?? null;
                                        @endphp

                                        @if($fotoKos && $fotoKos->path_foto)
                                            @if(str_starts_with($fotoKos->path_foto, '/9j/') || str_starts_with($fotoKos->path_foto, 'data:image'))
                                                <img src="data:image/jpeg;base64,{{ $fotoKos->path_foto }}" alt="Gambar Kos"
                                                    class="w-full h-full object-cover rounded-2xl" />
                                            @endif
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center rounded-2xl">
                                                <i class="bi bi-image text-gray-300 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @php
                                    \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
                                    \Midtrans\Config::$isProduction = false;
                                    \Midtrans\Config::$isSanitized = true;
                                    \Midtrans\Config::$is3ds = true;

                                    $orderId = 'BOOK-' . $booking->id . '-' . time() . '-' . rand(1000, 9999);

                                    $params = array(
                                        'transaction_details' => array(
                                            'order_id' => $orderId,
                                            'gross_amount' => (int) $booking->total_harga,
                                        ),
                                        'customer_details' => array(
                                            'first_name' => auth()->user()->name,
                                            'email' => auth()->user()->email,
                                            'phone' => auth()->user()->no_hp ?? '-',
                                        ),
                                        'item_details' => [
                                            [
                                                'id' => 'booking-' . $booking->id,
                                                'quantity' => 1,
                                                'name' => 'Booking Kos: ' . ($booking->kos->nama_kos ?? 'Kos'),
                                                'price' => (int) $booking->total_harga,
                                            ]
                                        ],
                                    );
                                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                                @endphp
                                <div class="mt-4 flex justify-center gap-4">
                                    <button id="pay-button-{{ $booking->id }}"
                                        onclick="payNow('{{ $snapToken }}', {{ $booking->id }})"
                                        class="btn w-40 h-9 text-white text-xs font-medium rounded-full bg-[#5C00CC]">Bayar
                                        Sekarang</button>
                                    <button
                                        onclick="confirmCancel({{ $booking->id }}, '{{ $booking->kos->nama_kos ?? 'Kos' }}', 'menunggu_pembayaran')"
                                        class="btn w-40 h-9 text-[#FF0000] text-xs font-medium rounded-full bg-white">
                                        Batalkan</button>
                                </div>
                            </div>
                        @endforeach
                        @if($pembayaranBookings->count() == 0)
                            <p class="text-gray-500">Tidak ada booking</p>
                        @endif
                    </div>

                    <!-- Tab 3: Check-in -->
                    <div id="check-in" class="tab-content">
                        <p class="text-md text-black md:text-md font-semibold mb-6">Check-in</p>
                        <div class="flex items-center gap-6 text-[11px] text-gray-500 mb-7">
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button active w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="menunggu-persetujuan"></button>
                                <!-- <span class="w-13 h-[6px] rounded-full bg-[#5C00CC]"></span> -->
                                <span>Pemilik setuju</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="pembayaran"></button>
                                <span>Bayar sewa</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="check-in"></button>
                                <span>Check-in</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-gray-200"
                                    data-tab="cancel"></button>
                                <span>Cancel</span>
                            </div>
                        </div>

                        @foreach($checkinBookings as $booking)
                            <!-- Card pesanan untuk Check-in -->
                            <div class="border-2 border-gray-200 rounded-[20px] px-6 py-6 mb-6 max-w-xl w-full">
                                <div class="flex justify-between gap-4">
                                    <!-- Left info -->
                                    <div class="space-y-3 text-xs">
                                        <div>
                                            <p class="text-sm text-black font-semibold">
                                                {{ $booking->kos->nama_kos ?? 'Kos' }}
                                            </p>
                                            <span
                                                class="text-black inline-block mt-2 px-2 py-[2px] rounded-full border border-gray-400 text-[10px]">
                                                Kos
                                                {{ $booking->kos->tipe_kos == 'putra' ? 'Putra' : ($booking->kos->tipe_kos == 'putri' ? 'Putri' : 'Campur') }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2 mt-4">
                                            <span class="text-gray-500 w-20">Mulai Sewa:</span>
                                            <span class="font-medium text-black hidden md:inline">
                                                @if($booking->tanggal_checkin)
                                                    {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}
                                                @else
                                                    Belum ditentukan
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Lama Sewa:</span>
                                            @php
                                                $displayDurasi = match ($booking->periode_sewa) {
                                                    'harian' => $booking->durasi_sewa . ' Hari',
                                                    'mingguan' => $booking->durasi_sewa . ' Minggu',
                                                    'bulanan' => $booking->durasi_sewa . ' Bulan',
                                                    '3_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    '6_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    'tahunan' => $booking->durasi_sewa . ' Tahun',
                                                    default => $booking->durasi_sewa . ' Bulan'
                                                };
                                            @endphp

                                            <span class="font-medium text-black">
                                                {{ $displayDurasi }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Durasi Sewa:</span>
                                            @php
                                                $displayPeriode = match ($booking->periode_sewa) {
                                                    'harian' => 'Harian',
                                                    'mingguan' => 'Mingguan',
                                                    'bulanan' => 'Bulanan',
                                                    '3_bulanan' => '3 Bulanan',
                                                    '6_bulanan' => '6 Bulanan',
                                                    'tahunan' => 'Tahunan',
                                                    default => 'Bulanan'
                                                };
                                            @endphp
                                            <span class="font-medium text-black">
                                                {{ $displayPeriode }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Right image -->
                                    <div
                                        class="flex w-30 h-26 rounded-2xl border border-gray-200 items-center justify-center">
                                        @php
                                            $fotoKos = $booking->kos->fotoKos->where('tipe', 'kamar')->first() ?? null;
                                        @endphp
                                        @if($fotoKos && $fotoKos->path_foto)
                                            @if(str_starts_with($fotoKos->path_foto, '/9j/') || str_starts_with($fotoKos->path_foto, 'data:image'))
                                                <img src="data:image/jpeg;base64,{{ $fotoKos->path_foto }}" alt="Gambar Kos"
                                                    class="w-full h-full object-cover rounded-2xl" />
                                            @endif
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center rounded-2xl">
                                                <i class="bi bi-image text-gray-300 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class=" flex justify-start">
                                    <div
                                        class="mt-3 inline-flex px-2 py-1 rounded-[6px] bg-[#EDF9F4] text-[10px] text-[#016034] border-2 border-[#AADEC6] font-medium">
                                        Selesai</div>
                                </div>

                                <div class="mt-6 text-xs">
                                    <p class="text-gray-500 mt-6">Salin kode ini atau screenshots halaman</p>
                                    <div class="flex gap-2 flex items-center mt-2">
                                        <span class="text-gray-500 w-20">ID Check-in:</span>
                                        <span id="copy-id-checkin" class="font-medium text-black">
                                            {{ $booking->kode_checkin ?? 'N/A' }}
                                        </span>
                                        <button type="button" class="js-clipboard tooltip"
                                            aria-label="Copy text to clipboard" data-clipboard-target="#copy-id-checkin"
                                            data-clipboard-action="copy" data-clipboard-success-text="Copied!">
                                            <span class="tooltip-toggle flex items-center justify-center">
                                                <span
                                                    class="js-clipboard-default icon-[tabler--clipboard] size-5 text-gray-400 transition"></span>
                                                <span
                                                    class="js-clipboard-success icon-[tabler--clipboard-check] text-primary hidden size-5"></span>
                                            </span>
                                            <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible"
                                                role="tooltip">
                                                <span class="tooltip-body js-clipboard-success-text text-xs">Copy</span>
                                            </span>
                                        </button>
                                    </div>
                                    @php
                                        $ulasanUntukKos = $userUlasan->firstWhere('kos_id', $booking->kos_id);
                                    @endphp
                                    <button type="button" 
                                            onclick="{{ $ulasanUntukKos ? 
                                                'openEditUlasanModal('.$booking->id.','.$booking->kos_id.',\''.addslashes($booking->kos->nama_kos).'\','.$ulasanUntukKos->id.')' : 
                                                'openUlasanModal('.$booking->id.','.$booking->kos_id.',\''.addslashes($booking->kos->nama_kos).'\')' 
                                            }}"
                                            id="ulasan-btn-{{ $booking->id }}"
                                            class="btn btn-outline h-6 mt-3 px-2 py-1 rounded-[6px] text-[10px] text-[#5C00CC] border border-[#5C00CC] font-medium">
                                        {{ $ulasanUntukKos ? 'Edit Ulasan' : 'Beri Ulasan' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        @if($checkinBookings->count() == 0)
                            <p class="text-gray-500">Tidak ada booking</p>
                        @endif
                    </div>
                    <div id="ulasan-modal"
                        class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 hidden"
                        role="dialog" tabindex="-1">
                        <div
                            class="modal-dialog overlay-open:mt-12 overlay-open:duration-300 transition-all ease-out">
                            <form id="form-ulasan" action="{{ route('ulasan.store') }}" method="POST">
                                @csrf
                                <input type="hidden" id="booking_id" name="booking_id" value="">
                                <input type="hidden" id="kos_id" name="kos_id" value="">
                                <input type="hidden" id="rating_value" name="rating" value="1">
                                <input type="hidden" id="ulasan_id" name="ulasan_id" value="">
                                <input type="hidden" id="is_edit" name="is_edit" value="0">

                                <div class="modal-content bg-white">
                                    <div class="modal-header pb-3 pt-5">
                                        <p id="modal-title" class="modal-title text-lg text-black">Penilaian dan Ulasan</p>
                                        <button type="button"
                                            onclick="closeModal()"
                                            class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                                            aria-label="Close">
                                            <span class="icon-[tabler--x] size-4"></span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-black text-sm py-0">
                                        <div class="flex items-center mb-4 gap-4">
                                            <div class="" id="raty-primary"></div><div class="h-6" data-hint></div>
                                        </div>
                                        
                                        <textarea name="ulasan" id="ulasan_text" class="bg-white w-full h-20 p-2 focus:outline-none rounded-md border-[1px] border-gray-400" placeholder="Berikan ulasanmu hehe" ></textarea>
                                    </div>
                                    <div class="modal-footer text-black flex justify-start pb-5">
                                        <button type="submit" id="submit-btn"
                                            class="btn btn-outline h-7 mt-3 px-2 py-1 rounded-[6px] text-[#5C00CC] border border-[#5C00CC] font-medium">
                                            Kirim
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Tambah di bawah modal ulasan -->
                    <div id="confirm-modal" class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 hidden"
                        role="dialog" tabindex="-1">
                        <div class="modal-dialog overlay-open:mt-12 overlay-open:duration-300 transition-all ease-out">
                            <div class="modal-content bg-white rounded-[20px] p-6">
                                <!-- Icon sukses -->
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Judul dinamis -->
                                    <h3 id="confirm-title" class="text-lg font-semibold text-gray-900 mb-2">
                                        Ulasan Terkirim!
                                    </h3>
                                    
                                    <!-- Pesan dinamis -->
                                    <p id="confirm-message" class="text-gray-600 text-sm">
                                        Ulasan Anda telah berhasil dikirim.
                                    </p>
                                </div>
                                
                                <!-- Tombol aksi -->
                                <div class="flex justify-center mt-6">
                                    <button type="button"
                                            onclick="closeConfirmModal()"
                                            class="btn btn-primary h-10 px-6 rounded-[10px] text-white bg-[#5C00CC] border-[#5C00CC] font-medium">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <!-- Tab 4: Cancel -->
                    <div id="cancel" class="tab-content">
                        <p class="text-md text-black md:text-md font-semibold mb-6">Menunggu persetujuan</p>
                        <div class="flex items-center gap-6 text-[11px] text-gray-500 mb-7">
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button active w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="menunggu-persetujuan"></button>
                                <span>Pemilik setuju</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="pembayaran"></button>
                                <span>Bayar sewa</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="check-in"></button>
                                <span>Check-in</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <button class="tab-button w-13 h-[6px] rounded-full bg-[#5C00CC]"
                                    data-tab="cancel"></button>
                                <span>Cancel</span>
                            </div>
                        </div>

                        @foreach($cancelBookings as $booking)
                            <!-- Card pesanan -->
                            <div class="max-w-xl mb-6">
                                <div class="border-2 border-gray-200 rounded-[20px] px-6 py-6 flex justify-between">
                                    <!-- Left info -->
                                    <div class="space-y-3 text-xs">
                                        <div>
                                            <p class="text-sm text-black font-semibold">
                                                {{ $booking->kos->nama_kos ?? 'Kos' }}
                                            </p>
                                            <span
                                                class="text-black inline-block mt-2 px-2 py-[2px] rounded-full border border-gray-400 text-[10px]">
                                                Kos
                                                {{ $booking->kos->tipe_kos == 'putra' ? 'Putra' : ($booking->kos->tipe_kos == 'putri' ? 'Putri' : 'Campur') }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2 mt-4">
                                            <span class="text-gray-500 w-20">Mulai Sewa:</span>
                                            <span class="font-medium text-black hidden md:inline">
                                                @if($booking->tanggal_checkin)
                                                    {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}
                                                @else
                                                    Belum ditentukan
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Lama Sewa:</span>
                                            @php
                                                $displayDurasi = match ($booking->periode_sewa) {
                                                    'harian' => $booking->durasi_sewa . ' Hari',
                                                    'mingguan' => $booking->durasi_sewa . ' Minggu',
                                                    'bulanan' => $booking->durasi_sewa . ' Bulan',
                                                    '3_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    '6_bulanan' => ($booking->durasi_sewa) . ' Bulan',
                                                    'tahunan' => $booking->durasi_sewa . ' Tahun',
                                                    default => $booking->durasi_sewa . ' Bulan'
                                                };
                                            @endphp

                                            <span class="font-medium text-black">
                                                {{ $displayDurasi }}
                                            </span>
                                        </div>

                                        <div class="flex gap-2">
                                            <span class="text-gray-500 w-20">Durasi Sewa:</span>
                                            @php
                                                $displayPeriode = match ($booking->periode_sewa) {
                                                    'harian' => 'Harian',
                                                    'mingguan' => 'Mingguan',
                                                    'bulanan' => 'Bulanan',
                                                    '3_bulanan' => '3 Bulanan',
                                                    '6_bulanan' => '6 Bulanan',
                                                    'tahunan' => 'Tahunan',
                                                    default => 'Bulanan'
                                                };
                                            @endphp
                                            <span class="font-medium text-black">
                                                {{ $displayPeriode }}
                                            </span>
                                        </div>
                                        @if ($booking->status == 'ditolak')
                                            <div
                                                class="inline-flex px-2 py-1 rounded-[6px] bg-yellow-100 text-[10px] text-yellow-800 border-2 border-yellow-200 font-medium">
                                                Ditolak
                                            </div>
                                        @elseif ($booking->status == 'dibatalkan')
                                            <div
                                                class="inline-flex px-2 py-1 rounded-[6px] bg-red-100 text-[10px] text-red-800 border-2 border-red-200 font-medium">
                                                Dibatalkan
                                            </div>
                                        @endif

                                    </div>

                                    <!-- Right image placeholder -->
                                    <div
                                        class="flex w-30 h-26 rounded-2xl border border-gray-200 items-center justify-center overflow-hidden">
                                        @php
                                            $fotoKos = $booking->kos->fotoKos->where('tipe', 'kamar')->first() ?? null;
                                        @endphp

                                        @if($fotoKos && $fotoKos->path_foto)
                                            @if(str_starts_with($fotoKos->path_foto, '/9j/') || str_starts_with($fotoKos->path_foto, 'data:image'))
                                                <img src="data:image/jpeg;base64,{{ $fotoKos->path_foto }}" alt="Gambar Kos"
                                                    class="w-full h-full object-cover rounded-2xl" />
                                            @endif
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center rounded-2xl">
                                                <i class="bi bi-image text-gray-300 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($cancelBookings->count() == 0)
                            <p class="text-gray-500">Tidak ada booking</p>
                        @endif
                    </div>
                </div>
                <div id="cancel-modal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-[20px] p-6 max-w-md w-full">
                        <h3 class="text-lg font-bold text-black mb-4">Batalkan Booking</h3>
                        <p class="text-gray-600 mb-2" id="cancel-kos-name">
                            <!-- Nama kos akan diisi oleh JS -->
                        </p>
                        <p class="text-gray-600 mb-6" id="cancel-status-info">
                            <!-- Status info akan diisi oleh JS -->
                        </p>
                        <div class="flex justify-end gap-3">
                            <button onclick="closeCancelModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg">
                                Tidak
                            </button>
                            <button onclick="processCancel()"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
                                id="confirm-cancel-btn">
                                Ya, Batalkan
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="{{ asset('js/flyonui.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script src="{{ asset('js/helper-clipboard.js') }}"></script>
    <script src="{{ asset('js/raty.js') }}"></script>
    <script>
        let ratingInstance = null;
        let currentBookingId = null;
        let currentKosId = null;
        let currentUlasanId = null; // Untuk edit

        // ===== FUNGSI BUKA MODAL =====
        function openUlasanModal(bookingId, kosId, kosName) {
            console.log('Opening modal for:', { bookingId, kosId, kosName });
            
            // Reset mode ke "tambah baru"
            document.getElementById('is_edit').value = '0';
            document.getElementById('ulasan_id').value = '';
            currentUlasanId = null;
            
            // Set data ke form
            document.getElementById('booking_id').value = bookingId;
            document.getElementById('kos_id').value = kosId;
            currentBookingId = bookingId;
            currentKosId = kosId;
            
            // Reset form
            document.getElementById('ulasan_text').value = '';
            if (ratingInstance) {
                ratingInstance.score(1);
            }
            document.getElementById('rating_value').value = 1;
            
            // Set judul dan tombol (mode add)
            document.getElementById('modal-title').textContent = 'Penilaian untuk ' + kosName;
            document.getElementById('submit-btn').textContent = 'Kirim';
            
            // Buka modal
            document.getElementById('ulasan-modal').classList.remove('hidden');
            document.getElementById('ulasan-modal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        // ===== FUNGSI EDIT ULASAN =====
        function openEditUlasanModal(bookingId, kosId, kosName, ulasanId) {
            console.log('Opening EDIT modal for:', { bookingId, kosId, kosName, ulasanId });
            
            // Set mode edit
            document.getElementById('is_edit').value = '1';
            document.getElementById('ulasan_id').value = ulasanId;
            currentUlasanId = ulasanId;
            
            // Set data ke form
            document.getElementById('booking_id').value = bookingId;
            document.getElementById('kos_id').value = kosId;
            currentBookingId = bookingId;
            currentKosId = kosId;
            
            // Ambil data ulasan yang sudah ada (AJAX)
            fetch(`/api/ulasan/${ulasanId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.ulasan) {
                        // Isi form dengan data lama
                        const ulasan = data.ulasan;
                        document.getElementById('ulasan_text').value = ulasan.komentar || '';
                        
                        if (ratingInstance) {
                            ratingInstance.score(ulasan.rating);
                        }
                        document.getElementById('rating_value').value = ulasan.rating;
                    }
                })
                .catch(error => {
                    console.error('Error fetching ulasan:', error);
                });
            
            // Set judul dan tombol (mode edit)
            document.getElementById('modal-title').textContent = 'Edit Ulasan untuk ' + kosName;
            document.getElementById('submit-btn').textContent = 'Update';
            
            // Buka modal
            document.getElementById('ulasan-modal').classList.remove('hidden');
            document.getElementById('ulasan-modal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function showConfirmModal(title, message) {
            // Set judul dan pesan
            document.getElementById('confirm-title').textContent = title;
            document.getElementById('confirm-message').textContent = message;
            
            // Tampilkan modal
            document.getElementById('confirm-modal').classList.remove('hidden');
            document.getElementById('confirm-modal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeConfirmModal() {
            // Sembunyikan modal
            document.getElementById('confirm-modal').classList.remove('open');
            document.getElementById('confirm-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // ===== HANDLE FORM SUBMIT (UNTUK ADD DAN EDIT) =====
        document.getElementById('form-ulasan')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const isEdit = document.getElementById('is_edit').value === '1';
            const ulasanId = document.getElementById('ulasan_id').value;
            
            let url = this.action;
            // let method = 'POST';
            
            if (isEdit && ulasanId) {
                url = `/ulasan/${ulasanId}`; // Route untuk update
                formData.append('_method', 'PUT');
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showConfirmModal(
                        isEdit ? 'Ulasan Diupdate!' : 'Ulasan Terkirim!',
                        isEdit ? 'Perubahan ulasan Anda telah disimpan.' : 'Terima kasih atas ulasan Anda!'
                    );
                    
                    closeModal();
                    
                    // Update tombol di card
                    updateButtonOnCard(
                        'Edit Ulasan', 
                        currentBookingId, 
                        currentKosId, 
                        data.kosName || 'Kos',
                        data.ulasan?.id || data.ulasanId
                    );
                    
                } else {
                    alert(data.error || 'Gagal mengirim ulasan');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan');
            }
        });

        // ===== FUNGSI UPDATE TOMBOL DI CARD =====
        function updateButtonOnCard(text, bookingId, kosId, kosName, ulasanId) {
            console.log('Updating button with:', { bookingId, kosId, ulasanId });
            const button = document.getElementById(`ulasan-btn-${bookingId}`);
            // const buttons = document.querySelectorAll(`button[onclick*="openUlasanModal(${bookingId}"]`);
            // const button = buttons[0];
            if (button) {
                // Ganti teks tombol
                button.textContent = text;
                
                // Ganti onclick untuk edit
                if (text === 'Edit Ulasan') {
                    const safeKosName = kosName.replace(/'/g, "\\'");
                    button.setAttribute('onclick', `openEditUlasanModal(${bookingId}, ${kosId}, '${safeKosName}', ${ulasanId})`);
                    console.log('Button updated!');
                    // Atau simpan di data attribute
                    // button.dataset.ulasanId = currentUlasanId || '';
                }
            } else {
                console.error('Button not found for booking:', bookingId);
            }
        }

        // ===== FUNGSI TUTUP MODAL =====
        function closeModal() {
            document.getElementById('ulasan-modal').classList.remove('open');
            document.getElementById('ulasan-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // PASTIKAN RATY DI INIT
        setTimeout(() => {
            initRating();
        }, 100);

        function initRating() {
            const element = document.querySelector('#raty-primary');
            if (!element) return;
            
            if (element.children.length > 0) {
                console.log('Raty sudah ada, pakai yang existing');
                // Cuma update score saja
                if (window.Raty && ratingInstance) {
                    ratingInstance.score(score);
                }
                return;
            }

            
            ratingInstance = new Raty(element, {
                starType: 'i',
                score: 1,
                starOff: 'icon-[tabler--star-filled] opacity-20 size-4 text-primary',
                starOn: 'icon-[tabler--star-filled] size-4 text-[#5C00CC]',
                hints: ['Sangat Buruk', 'Buruk', 'Biasa', 'Baik', 'Sangat Baik'],
                target: '[data-hint]',
                targetFormat: '{score}',
                click: function(score) {
                    console.log('Raty clicked, score:', score);
                    document.getElementById('rating_value').value = score;
                    console.log('Rating input value set to:', document.getElementById('rating_value').value);
                }
            });
            
            ratingInstance.init();
            document.getElementById('rating_value').value = 1;
        }
    </script>
    <script>
        function forceCloseModal() {
            // 1. Hapus semua class yang bikin modal terbuka
            document.querySelectorAll('.overlay').forEach(modal => {
                modal.classList.remove('open', 'opened', 'overlay-open', 'show');
                modal.classList.add('hidden');
            });

            
            // 2. Remove backdrop element jika ada
            document.querySelectorAll('.modal-backdrop, .overlay-backdrop').forEach(el => {
                el.remove();
            });
            
            // 3. Enable scroll dan hapus class dari body
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open', 'overflow-hidden');
            
            // 4. Force hide dengan CSS inline
            document.querySelectorAll('.overlay').forEach(modal => {
                modal.style.display = 'none';
                modal.style.visibility = 'hidden';
                modal.style.opacity = '0';
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            function switchTab(tabId) {
                // Remove active class dari semua
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class ke button dan content yang dipilih
                const targetButton = document.querySelector(`[data-tab="${tabId}"]`);
                const targetContent = document.getElementById(tabId);

                if (targetButton) targetButton.classList.add('active');
                if (targetContent) targetContent.classList.add('active');
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);

                    window.location.hash = tabId;
                });
            });

            if (window.location.hash) {
                const hashTabId = window.location.hash.substring(1);
                if (hashTabId && document.querySelector(`[data-tab="${hashTabId}"]`)) {
                    setTimeout(() => {
                        document.querySelector(`[data-tab="${hashTabId}"]`).click();
                    }, 300);
                } else {
                    switchTab('menunggu-persetujuan');
                }
            }

            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (tabParam && document.getElementById(tabParam)) {
                setTimeout(() => {
                    switchTab(tabParam);
                }, 100);
            }
        });
    </script>
    <script>
        let bookingToCancel = null;
        let cancelStatus = null;

        function confirmCancel(bookingId, kosName, status) {
            bookingToCancel = bookingId;
            cancelStatus = status;

            document.getElementById('cancel-kos-name').textContent = `Apakah Anda yakin ingin membatalkan booking untuk kos "${kosName}"?`;
            document.getElementById('cancel-status-info').textContent = `Status booking saat ini: ${status.replace('_', ' ')}`;

            document.getElementById('cancel-modal').classList.remove('hidden');
            document.getElementById('cancel-modal').classList.add('flex');
        }

        function closeCancelModal() {
            bookingToCancel = null;
            cancelStatus = null;
            document.getElementById('cancel-modal').classList.add('hidden');
            document.getElementById('cancel-modal').classList.remove('flex');
        }

        function processCancel() {
            if (!bookingToCancel || !cancelStatus) return;

            const url = `/cancel-booking/${bookingToCancel}`;
            const data = {
                status: cancelStatus,
                _token: '{{ csrf_token() }}'
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Booking berhasil dibatalkan.');
                        location.reload();
                    } else {
                        alert('Gagal membatalkan booking. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                })
                .finally(() => {
                    closeCancelModal();
                });
        }
    </script>
</body>

</html>