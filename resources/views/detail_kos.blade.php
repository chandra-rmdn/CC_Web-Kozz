<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Koszzz - Temukan kos favoritmu</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .flatpickr-input {
            border: none !important;
            background: transparent !important;
            outline: none !important;
            width: 100% !important;
            cursor: pointer;
        }

        .flatpickr-calendar {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
        }

        .flatpickr-day.selected {
            background-color: #5C00CC !important;
            border-color: #5C00CC !important;
        }

        #chat-overlay .absolute.inset-y-0 {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* Backdrop animation */
        #chat-backdrop {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Better scrollbar */
        #chat-overlay div[h-calc]::-webkit-scrollbar {
            width: 6px;
        }

        #chat-overlay div[h-calc]::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        /* Glass effect backdrop */
        #chat-backdrop {
            background: rgba(0, 0, 0, 0.4);
            /* 40% opacity - kurang pekat */
            backdrop-filter: blur(2px);
            /* subtle blur */
        }
    </style>
    
</head>

<body class="bg-[#F6F5FB]">
    <header class="w-full flex items-center justify-between px-4 md:px-[88px] py-[14px] bg-[#F6F5FB]">
        <button class="flex items-center gap-[7px]" onclick="window.location.href='/'">
            <div class="icon-home text-black"><i class="bi bi-houses-fill text-2xl md:text-[34px]"></i></div>
            <span class="text-2xl md:text-[32px] font-poppins font-bold text-[#5C00CC]">Koszzz</span>
        </button>
        @auth
            <script>
                window.authUser = {
                    id: {{ auth()->id() }},
                    name: '{{ auth()->user()->name }}',
                    role: '{{ auth()->user()->role }}',
                    email: '{{ auth()->user()->email }}'
                };
            </script>
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
                        <li><a class="dropdown-item text-black gap-1 text-[15px]" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                                Favorit
                            </a></li>
                        <li><a class="dropdown-item text-black gap-1 text-[15px]" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                                Pesan
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

    <!-- Content -->
    <main class="px-2 md:px-[61px] pt-[25px]">
        <!-- Gallery -->
        <section class="bg-white rounded-t-[20px] shadow-sm p-4 md:pt-[38px] md:px-[120px]">
            @php
                $FotoKamar = $kos->fotoKos->where('tipe', 'kamar');
                $fotoBangunan = $kos->fotoKos->where('tipe', 'bangunan')->first();
                $fotoKamarUtama = $FotoKamar->first();
                $fotoKamarLain = $FotoKamar->skip(1)->take(3);
                $fotoKotakKecil = collect([$fotoBangunan])->merge($fotoKamarLain)->filter();
            @endphp
            <div class="grid grid-cols-4 gap-4 h-40 md:h-[345px] hidden md:grid">
                <button type="button" class="col-span-2 bg-gray-200 border border-gray-300 rounded-l-[20px] w-full h-full max-h-[345px]"
                    aria-haspopup="dialog" aria-expanded="false" aria-controls="fullscreen-modal-dekstop"
                    data-overlay="#fullscreen-modal-dekstop">
                    @if($fotoKamarUtama && (str_starts_with($fotoKamarUtama->path_foto, '/9j/') || str_starts_with($fotoKamarUtama->path_foto, 'data:image')))
                        <img src="data:image/jpeg;base64,{{ $fotoKamarUtama->path_foto }}"
                            class="w-full h-full max-h-[345px] object-cover rounded-l-[20px]" />
                    @else
                        <div
                            class="w-full h-full max-h-[345px] bg-gradient-to-br from-purple-100 to-blue-100 
                                                                                    flex items-center justify-center rounded-l-[20px]">
                            <i class="bi bi-house text-[#5C00CC] text-4xl"></i>
                        </div>
                    @endif
                </button>
                <div class="col-span-2 grid grid-rows-2 grid-cols-2 gap-4">
                    @foreach($fotoKotakKecil as $index => $foto)
                        @php
                            if ($index == 1) {
                                $roundedClass = 'rounded-tr-[20px]'; // Kotak pertama (atas kanan)
                            } elseif ($index == 3) {
                                $roundedClass = 'rounded-br-[20px]'; // Kotak keempat (bawah kanan)
                            } else {
                                $roundedClass = '';
                            }
                            $icon = $foto->tipe == 'bangunan' ? 'bi-building' : 'bi-door-closed';
                        @endphp
                        <button type="button" class="bg-gray-200 w-full h-full border border-gray-300 overflow-hidden {{ $roundedClass }}"
                            aria-haspopup="dialog" aria-expanded="false" aria-controls="fullscreen-modal-dekstop"
                            data-overlay="#fullscreen-modal-dekstop">
                            @if(str_starts_with($foto->path_foto, '/9j/') || str_starts_with($foto->path_foto, 'data:image'))
                                <img src="data:image/jpeg;base64,{{ $foto->path_foto }}"
                                    class="w-full h-full max-h-[164.5px] object-cover"
                                    alt="{{ $foto->tipe == 'bangunan' ? 'Bangunan' : 'Kamar' }}" />
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <i class="bi {{ $icon }} text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </button>
                    @endforeach
                    @for($i = $fotoKotakKecil->count(); $i < 4; $i++)
                        @php
                            if ($i == 1)
                                $roundedClass = 'rounded-tr-[20px]';
                            elseif ($i == 3)
                                $roundedClass = 'rounded-br-[20px]';
                            else
                                $roundedClass = '';
                        @endphp
                        <div
                            class="bg-gray-200 w-full h-full overflow-hidden {{ $roundedClass }} flex items-center justify-center">
                            <i class="bi bi-image text-gray-400 text-2xl"></i>
                        </div>
                    @endfor
                    <div id="fullscreen-modal-dekstop"
                        class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 hidden" role="dialog"
                        tabindex="-1">
                        <div class="modal-dialog max-w-[70vw]">
                            <div class="modal-content h-full max-h-none justify-between bg-white">
                                <div class="modal-header">
                                    <button type="button" class="btn btn-text btn-circle btn-sm absolute start-3 top-3"
                                        aria-label="Close" data-overlay="#fullscreen-modal-dekstop">
                                        <span class="icon-[tabler--x] size-4"></span>
                                    </button>
                                </div>
                                <div class="modal-body grow px-30 py-2">
                                    <div id="horizontal-thumbnails"
                                        data-carousel='{"loadingClasses": "opacity-0", "isInfiniteLoop": true, "slidesQty": 1 }'
                                        class="relative w-full">
                                        <div class="carousel">
                                            <div class="carousel-body h-[76vh] opacity-0">
                                                @foreach($kos->fotoKos as $index => $foto)
                                                    <!-- Slide 1 -->
                                                    <div class="carousel-slide">
                                                        <div class="flex size-full justify-center">
                                                            @if(str_starts_with($foto->path_foto, '/9j/') || str_starts_with($foto->path_foto, 'data:image'))
                                                                <img src="data:image/jpeg;base64,{{ $foto->path_foto }}"
                                                                    class="size-full object-cover"
                                                                    alt="Foto {{ $loop->iteration }} - {{ $foto->tipe }}" />
                                                            @else
                                                                <div
                                                                    class="flex size-full items-center justify-center bg-gray-100">
                                                                    <i class="bi bi-image text-gray-400 text-6xl"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if($kos->fotoKos->count() == 0)
                                                    <div class="carousel-slide">
                                                        <div class="flex size-full justify-center items-center">
                                                            <div class="text-center">
                                                                <i class="bi bi-image text-gray-400 text-6xl mb-4"></i>
                                                                <p class="text-gray-500">Tidak ada foto tersedia</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Previous Slide -->
                                            <button type="button"
                                                class="carousel-prev start-5 max-sm:start-3 carousel-disabled:opacity-50 size-9.5 bg-black opacity-30 flex items-center justify-center rounded-full shadow-base-300/20 shadow-sm">
                                                <span class="icon-[tabler--chevron-left] size-5 cursor-pointer"></span>
                                                <span class="sr-only">Previous</span>
                                            </button>
                                            <!-- Next Slide -->
                                            <button type="button"
                                                class="carousel-next end-5 max-sm:end-3 carousel-disabled:opacity-50 size-9.5 bg-black opacity-30 flex items-center justify-center rounded-full shadow-base-300/20 shadow-sm">
                                                <span class="icon-[tabler--chevron-right] size-5"></span>
                                                <span class="sr-only">Next</span>
                                            </button>
                                            <div
                                                class="carousel-info absolute bottom-3 z-1 start-[50%] inline-flex -translate-x-[50%] justify-center rounded-lg bg-black opacity-30 px-4">
                                                <span class="carousel-info-current me-1">0</span>
                                                /
                                                <span class="carousel-info-total ms-1">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-60 md:hidden">
                <!-- <button type="button" class="bg-gray-200 rounded-[20px] w-full h-full md:hidden" aria-haspopup="dialog" aria-expanded="false" aria-controls="fullscreen-modal-mobile" data-overlay="#fullscreen-modal-mobile"></button> -->
                <div id="info" data-carousel='{ "loadingClasses": "opacity-0", "isInfiniteLoop": true, "slidesQty": 1 }'
                    class="relative w-full h-full md:hidden">
                    <div class="carousel h-full">
                        <div class="carousel-body h-full opacity-0">
                            <!-- Slide 1 -->
                            @foreach($kos->fotoKos as $foto)
                                <div class="carousel-slide">
                                    <div class="bg-base-200/50 flex h-full justify-center">
                                        @if(str_starts_with($foto->path_foto, '/9j/') || str_starts_with($foto->path_foto, 'data:image'))
                                            <img src="data:image/jpeg;base64,{{ $foto->path_foto }}"
                                                class="w-full h-full object-cover rounded-[20px]"
                                                alt="Foto {{ $loop->iteration }}" />
                                        @else
                                            <div
                                                class="w-full h-full bg-gray-200 rounded-[20px] flex items-center justify-center">
                                                <i class="bi bi-image text-gray-400 text-4xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($kos->fotoKos->count() == 0)
                                <div class="carousel-slide">
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-100 to-blue-100 rounded-[20px] flex items-center justify-center">
                                        <i class="bi bi-house text-[#5C00CC] text-4xl"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Previous Slide -->

                    <div
                        class="carousel-info absolute bottom-3 start-[50%] inline-flex -translate-x-[50%] justify-center rounded-lg bg-black opacity-30 px-2">
                        <span class="carousel-info-current text-xs me-1">0</span>
                        <span class="text-xs">/</span>
                        <span class="carousel-info-total text-xs ms-1">0</span>
                    </div>
                </div>
            </div>
            <section class="grid grid-cols-1 pt-4 md:pt-5 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)] gap-6 items-start">
                <!-- Left: detail -->
                <div class="md:pe-4">
                    <!-- Judul & meta -->
                    <div class="flex items-start justify-between mb-4 md:mb-[24px]">
                        <div>
                            <h1 class="text-lg md:text-xl text-black font-semibold mb-1">{{ $kos->nama_kos }}</h1>
                            <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                                <div class="w-12 h-4 inline-block text-center rounded-full border border-gray-500">
                                    {{ ucfirst($kos->tipe_kos) }}
                                </div>
                                <span>•</span>
                                @if($kos->kamar_tersedia == 0)
                                    <span class="text-red-500 font-medium">Penuh</span>
                                @elseif($kos->kamar_tersedia <= 3)
                                    <span>Sisa {{ $kos->kamar_tersedia }} kamar</span>
                                @else
                                    <span>{{ $kos->kamar_tersedia }} Kamar tersedia</span>
                                @endif
                                <span>•</span>
                                <span>★ {{ number_format($kos->mean_rating, 1) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($kos->alamatKos && $kos->alamatKos->alamat)
                                    {{ $kos->alamatKos->alamat }},
                                @endif
                            </p>
                        </div>
                        <button class="text-xs text-[#5C00CC] link link-animated [--link-color:purple]">Simpan</button>
                    </div>

                    <!-- Pemilik -->
                    <div class="flex items-center justify-between py-4 border-y border-gray-200 mb-4 md:mb-[24px]">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-200">
                                <img src="#" alt="Pemilik Kos" class="object-cover object-right rounded-full" />
                            </div>
                            <div>
                                <p class="text-sm text-black font-semibold">{{ $kos->owner->name }}</p>
                                <p class="text-[11px] text-gray-500">Pemilik / Pengelola</p>
                            </div>
                        </div>
                        <button onclick="showChat()"
                            class="btn btn-outline btn-xs text-xs rounded-full text-[#5C00CC] border-[#5C00CC] hover:bg-[#5C00CC] hover:text-white">
                            Tanya Pemilik
                        </button>

                        <!-- OVERLAY -->
                        <div id="chat-overlay" class="fixed inset-0 z-50 hidden">
                            <!-- BACKDROP dengan transparansi pas -->
                            <div id="chat-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                                onclick="hideChat()">
                            </div>

                            <!-- PANEL CHAT -->
                            <div
                                class="absolute inset-y-0 right-0 w-96 bg-white shadow-2xl transform transition-all duration-300 ease-out">

                                <!-- HEADER -->
                                <div class="flex items-center justify-between p-4 border-b">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 mr-3">
                                        </div>
                                        <div>
                                            <p class="font-semibold text-black"></p>
                                            <p class="text-sm text-gray-500"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- CHAT -->
                                <div class="p-4 h-[calc(100vh-140px)] overflow-y-auto">
                                    <div class="space-y-4">
                                        
                                    </div>
                                </div>

                                <!-- INPUT -->
                                <div class="absolute bottom-0 left-0 right-0 p-4 border-t bg-white">
                                    <div class="flex gap-2 items-center text-black">
                                        <input type="text" placeholder="Ketik pesan..."
                                            class="flex-1 border rounded-full px-4 py-2 focus:outline-none border-gray-300" />
                                        <button
                                            class="flex justify-center items-center bg-[#DCDCDC] text-white p-3 rounded-full w-10 h-10 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 30 32">
                                                <path fill="#000"
                                                    d="M2.078 3.965c-.407-1.265.91-2.395 2.099-1.801l24.994 12.495c1.106.553 1.106 2.13 0 2.684L4.177 29.838c-1.188.594-2.506-.536-2.099-1.801L5.95 16.001zm5.65 13.036L4.347 27.517l23.037-11.516L4.346 4.485L7.73 15H19a1 1 0 1 1 0 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spesifikasi kamar -->
                    <section class="mb-4 md:mb-[24px] md:me-[247.3px] md:mb-[24px]">
                        <h2 class="text-sm text-black font-semibold mb-2">Spek kamar</h2>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-y-1 text-xs text-gray-700">
                            <span>{{ $kos->kamar->first()->ukuran_kamar ?? '3x4' }} meter</span>
                        </div>
                    </section>

                    <!-- Fasilitas -->
                    <section class="mb-4 md:mb-[24px]">
                        <h2 class="text-sm text-black font-semibold mb-2">Fasilitas</h2>
                        @php
                            $kategoriList = ['fasilitasKamar', 'FasilitasKMandi'];
                            $adaFasilitas = false
                        @endphp
                        @foreach($kategoriList as $kategori)
                            @if($kos->fasilitas->where('kategori', $kategori)->count() > 0)
                                @php $adaFasilitas = true; @endphp
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-1 text-xs text-gray-700">
                                    @foreach($kos->fasilitas->where('kategori', $kategori) as $fasilitas)
                                        <span>{{ $fasilitas->nama_fasilitas }}</span>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                        @if(!$adaFasilitas)
                            <p class="text-xs text-gray-700">Belum ada fasilitas yang ditambahkan</p>
                        @endif
                    </section>

                    <!-- Catatan -->
                    <section class="mb-4 md:mb-[24px]">
                        <h2 class="text-sm text-black font-semibold mb-2">Catatan kos ini</h2>
                        <p class="text-xs text-gray-600">{{ $kos->deskripsi }}</p>
                    </section>

                    <!-- Fasilitas umum -->
                    <section class="mb-4 md:mb-[24px]">
                        <h2 class="text-sm text-black font-semibold mb-2">Fasilitas umum</h2>
                        @php
                            $kategoriList = ['fasilitasUmum', 'Parkir'];
                            $adaFasilitas = false
                        @endphp
                        @foreach($kategoriList as $kategori)
                            @if($kos->fasilitas->where('kategori', $kategori)->count() > 0)
                                @php $adaFasilitas = true; @endphp
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-1 text-xs text-gray-700">
                                    @foreach($kos->fasilitas->where('kategori', $kategori) as $fasilitas)
                                        <span>{{ $fasilitas->nama_fasilitas }}</span>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                        @if(!$adaFasilitas)
                            <p class="text-xs text-gray-700">Belum ada fasilitas yang ditambahkan</p>
                        @endif
                    </section>

                    <!-- Lokasi -->
                    <section class="mb-4 md:mb-[24px]">
                        @if($kos->alamatKos && $kos->alamatKos->alamat)
                            <h2 class="text-sm text-black font-semibold mb-2">Lokasi kos</h2>
                            <div class="mb-2 text-xs text-gray-600 flex items-start">
                                <i class="bi bi-geo-alt mr-2 mt-0.5"></i>
                                <span>{{ $kos->alamatKos->alamat }}</span>
                            </div>
                            <div id="map" class="w-full h-50 rounded-xl"></div>
                        @endif
                    </section>

                    <section class="mb-4 md:mb-[24px]">
                        <h2 class="text-sm text-black font-semibold mb-2">Catatan alamat</h2>
                        <div class="mb-2 text-xs text-gray-600 flex items-start">
                            <span>{{ $kos->alamatKos->catatan_alamat }}</span>
                        </div>
                    </section>

                    <!-- Penilaian & ulasan -->
                    <section class="mt-4 md:mt-[24px]">
                        <h2 class="text-sm text-black font-semibold mb-3">Penilaian dan ulasan</h2>
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-2xl text-black font-semibold">{{ number_format($kos->mean_rating, 1) }}
                                </p>
                                <p class="text-[11px] text-gray-500">{{ $semuaUlasan->count() }} ulasan</p>
                            </div>
                            <div class="select-floating max-w-sm w-[160px]">
                                <select class="select select-xs rounded-full text-gray-600 bg-white" id="filterUlasan"
                                    onchange="filterUlasan()" aria-label="floating label">
                                    <option value="terbaru">Ulasan Terbaru</option>
                                    <option value="tinggi">Rating Tinggi</option>
                                    <option value="rendah">Rating Rendah</option>
                                </select>
                                <label class="select-floating-label text-gray-600 bg-white" for="filterUlasan">Sort
                                    by:</label>
                            </div>
                        </div>

                        <div id="ulasan-container">
                            @if($ulasanDitampilkan->count() > 0)
                                @foreach($ulasanDitampilkan as $ulasan)
                                    <div class="flex gap-3 mb-6">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            @if($ulasan->user && $ulasan->user->name)
                                                {{ strtoupper(substr($ulasan->user->name, 0, 1)) }}
                                            @else
                                                <i class="bi bi-person text-gray-500"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between md:grid md:grid-cols-2">
                                                <p class="text-xs text-black font-semibold">
                                                    @if($ulasan->user && $ulasan->user->name)
                                                        @php
                                                            $nama = $ulasan->user->name;
                                                            $kata = explode(' ', $nama);
                                                            $namaTersamarkan = '';
                                                            foreach ($kata as $k) {
                                                                $namaTersamarkan .= str_repeat('*', strlen($k)) . ' ';
                                                            }
                                                        @endphp
                                                        {{ trim($namaTersamarkan) }}
                                                    @else
                                                        Anonymous
                                                    @endif
                                                </p>
                                                <span class="text-[11px] text-gray-500">★
                                                    {{ number_format($ulasan->rating, 1) }}</span>
                                            </div>
                                            <p class="text-[10px] text-gray-400 mt-1">
                                                {{ $ulasan->created_at->translatedFormat('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $ulasan->komentar }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm text-center py-4 md:me-[320px]">Belum ada ulasan</p>
                            @endif
                        </div>
                        <div class="flex items-center justify-between md:me-[320px]" id="pagination-controls">
                            <button id="prev-btn" class="btn btn-outline btn-xs btn-disabled text-xs text-[#000] border-[#000] hover:bg-[#DCDCDC]
                                {{ $semuaUlasan->count() <= 2 ? 'btn-disabled' : '' }}">
                                Sebelumnya
                            </button>
                            <span class="text-xs text-gray-500">
                                <span id="current-page">1</span> /
                                <span id="total-pages">{{ ceil($semuaUlasan->count() / 2) }}</span>
                            </span>
                            <button id="next-btn" class="btn btn-outline btn-xs text-xs text-[#000] border-[#000] hover:bg-[#DCDCDC]
                                {{ $semuaUlasan->count() <= 2 ? 'btn-disabled' : '' }}">
                                Selanjutnya
                            </button>
                        </div>
                    </section>
                </div>

                <!-- Right: harga & aksi -->
                <aside class="bg-white rounded-2xl p-4 md:p-6 h-max border border-gray-200 shadow-md">
                    @php
                        $kamar = $kos->kamar->where('status', 'tersedia')->first();
                        if ($kamar) {
                            $isTersedia = true;
                        } else {
                            $isTersedia = false;
                            $kamar = $kos->kamar->first();
                            $periodeTersedia = [];
                            $hargaDefault = 0;
                        }

                        $periodeTersedia = $kamar->hargaSewa->pluck('periode')->toArray();
                        $hargaDefault = $kamar->hargaSewa->where('periode', 'bulanan')->first()->harga ?? 0;
                        $periodeLabels = [
                            'bulanan' => 'Per bulan',
                            'harian' => 'Per hari',
                            'mingguan' => 'Per minggu',
                            '3_bulanan' => 'Per 3 bulan',
                            '6_bulanan' => 'Per 6 bulan',
                            'tahunan' => 'Per tahun'
                        ];
                    @endphp
                    <p class="text-xs text-gray-500 mb-1">Mulai dari</p>
                    <p class="text-lg text-black font-semibold mb-1" id="hargaDisplay">
                        @if($hargaDefault > 0)
                            Rp. {{ number_format($hargaDefault, 0, ',', '.') }}
                            <span class="text-xs font-normal text-gray-500">/bulan</span>
                        @else
                            Harga belum tersedia
                        @endif
                    </p>

                    <form action="{{ route('checkout') }}" method="GET" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="kos_id" value="{{ $kos->id }}">
                        <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">

                        <div
                            class="flex items-center justify-start px-2 bg-white border border-gray-400 rounded-md h-12">

                            <input type="text" name="tanggal_mulai" @if(!$isTersedia) disabled @endif
                                class="text-xs text-black bg-white no-focus w-1/2 flatpickr-input"
                                placeholder="Mulai kos" id="tanggalMulai" required readonly />
                            <div class="w-px h-9 mx-1 rounded-full bg-gray-400"></div>

                            @if(count($periodeTersedia) > 0)
                                <select class="w-full ps-1 no-focus text-xs text-black bg-white" id="periodeSewa"
                                    name="periode" onchange="updateHarga()" required>
                                    @foreach($periodeLabels as $periodeKey => $periodeLabel)
                                        @if(in_array($periodeKey, $periodeTersedia))
                                            @php
                                                $harga = $kamar->hargaSewa->where('periode', $periodeKey)->first()->harga ?? 0;
                                            @endphp
                                            <option value="{{ $periodeKey }}" data-harga="{{ $harga }}" {{ $periodeKey == 'bulanan' ? 'selected' : '' }}>
                                                {{ $periodeLabel }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        @if(!$isTersedia)
                                <div class="mb-3 mt-4 p-3 bg-yellow-50 w-full h-8 flex items-center border border-yellow-200 rounded">
                                    <p class="text-sm text-yellow-700">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Maaf, semua kamar sedang terisi.
                                    </p>
                                </div>
                        @else
                            <div class="flex items-center justify-between text-black text-xs mt-4 mb-3">
                                <span>Total harga</span>
                                <span class="font-semibold" id="totalHargaDisplay">
                                    @if($hargaDefault > 0)
                                        Rp. {{ number_format($hargaDefault, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        <input type="hidden" @if(!$isTersedia) disabled @endif name="harga_total" id="hargaTotalInput" value="{{ $hargaDefault }}">
                        <button type="submit" @if(!$isTersedia) disabled @endif onclick="console.log('Button clicked!')"
                            class="btn text-sm font-medium text-white rounded-lg w-full bg-[#5C00CC] mb-3">
                            Pesan Sekarang
                        </button>
                    </form>
                </aside>
            </section>
        </section>
    </main>
    <script src="{{ asset('js/flyonui.js') }}"></script>
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script src="{{ asset('js/id.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let chatVisible = false;
        let currentKosId = {{ $kos->id ?? 'null' }};
        let currentRoomId = null;
        let authUser = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // ==================== FUNGSI UTAMA ====================
        async function showChat() {
            if (!isUserLoggedIn()) {
                alert('Silakan login terlebih dahulu untuk memulai chat');
                return;
            }
            
            authUser = window.authUser;
            
            if (authUser.role !== 'penyewa') {
                alert('Hanya penyewa yang bisa mengirim chat ke pemilik');
                return;
            }
            
            await startNewChat(currentKosId);
        }

        // ==================== FUNGSI CHAT BARU ====================
        async function startNewChat(kosId) {
            try {
                const response = await fetch('/chat/rooms/get-or-create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ kos_id: kosId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    currentRoomId = data.room.id;
                    openChatOverlay(data.room);
                    await loadMessages(currentRoomId); // LOAD PESAN
                } else {
                    alert('Gagal memulai chat: ' + (data.error || 'Unknown error'));
                }
                
            } catch (error) {
                console.error('Error starting chat:', error);
                alert('Terjadi kesalahan saat memulai chat');
            }
        }

        // ==================== FUNGSI LOAD PESAN ====================
        async function loadMessages(roomId) {
            try {
                const response = await fetch(`/chat/rooms/${roomId}/messages`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    renderMessages(data.messages);
                }
                
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        // ==================== UPDATE FUNGSI FORMAT WAKTU ====================
        function formatTimeFromDB(dateString) {
            if (!dateString) return 'Baru saja';
            
            try {
                // Parse sebagai UTC dan biarkan JavaScript konversi ke waktu lokal
                const isoDate = dateString.includes(' ') 
                    ? dateString.replace(' ', 'T') + 'Z'  // "2025-12-31T10:58:11Z"
                    : dateString;
                
                const date = new Date(isoDate);
                
                if (isNaN(date.getTime())) {
                    // Fallback: ambil jam langsung dari string
                    if (dateString.includes(' ')) {
                        const timePart = dateString.split(' ')[1];
                        const [hour, minute] = timePart.split(':');
                        return `${hour}:${minute}`;
                    }
                    return 'Baru saja';
                }
                
                // Format sebagai waktu lokal Indonesia
                return date.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                
            } catch (e) {
                // Fallback terakhir
                if (dateString && dateString.includes(' ')) {
                    const timePart = dateString.split(' ')[1];
                    const [hour, minute] = timePart.split(':');
                    return `${hour}:${minute}`;
                }
                return 'Baru saja';
            }
        }

        // ==================== FUNGSI RENDER PESAN ====================
        function renderMessages(messages) {
            const messagesContainer = document.querySelector('#chat-overlay .space-y-4');
            if (!messagesContainer) return;
            
            if (!messages || messages.length === 0) {
                messagesContainer.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <p>Mulai percakapan dengan pemilik kos</p>
                        <p class="text-sm mt-1">Kirim pesan pertamamu!</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            messages.forEach(msg => {
                const isCurrentUser = msg.sender_id == authUser.id;
                const timeStr = formatTimeFromDB(msg.created_at); // PAKAI FUNGSI YANG SAMA
                
                html += `
                    <div class="${isCurrentUser ? 'flex justify-end' : 'flex justify-start'}">
                        <div class="max-w-xs">
                            <div class="${isCurrentUser 
                                ? 'bg-[#5C00CC] text-white p-3 rounded-2xl rounded-br-none' 
                                : 'bg-gray-100 text-black p-3 rounded-2xl rounded-bl-none'} text-sm">
                                ${msg.pesan}
                            </div>
                            <p class="text-xs text-gray-500 ${isCurrentUser ? 'text-right' : ''} mt-1">
                                ${timeStr} • ${isCurrentUser ? 'Terkirim' : 'Dibaca'}
                            </p>
                        </div>
                    </div>
                `;
            });
            
            messagesContainer.innerHTML = html;
            
            setTimeout(() => {
                const container = document.querySelector('#chat-overlay .overflow-y-auto');
                if (container) container.scrollTop = container.scrollHeight;
            }, 100);
        }

        // ==================== FUNGSI BUKA OVERLAY ====================
        function openChatOverlay(roomData) {
            const overlay = document.getElementById('chat-overlay');
            const panel = overlay.querySelector('.absolute.inset-y-0');

            overlay.classList.remove('hidden');
            
            setTimeout(() => {
                panel.style.transform = 'translateX(0)';
            }, 10);

            chatVisible = true;
            document.body.style.overflow = 'hidden';
            
            // Update header dengan data pemilik
            updateChatHeader(roomData);
        }

        function updateChatHeader(roomData) {
            if (roomData.kos && roomData.kos.owner) {
                const headerName = document.querySelector('#chat-overlay .font-semibold');
                const headerRole = document.querySelector('#chat-overlay .text-sm.text-gray-500');
                
                if (headerName) headerName.textContent = roomData.kos.owner.name;
                if (headerRole) headerRole.textContent = 'Pemilik Kos';
            }
        }

        // ==================== FUNGSI KIRIM PESAN ====================
        async function sendMessage() {
            if (!currentRoomId) return;
            
            const input = document.querySelector('#chat-overlay input[type="text"]');
            const message = input.value.trim();
            
            if (message === '') return;
            
            // BUAT ID UNTUK TEMP MESSAGE
            const tempMsgId = 'temp-' + Date.now();
            
            try {
                // Tampilkan pesan loading sementara
                const messagesContainer = document.querySelector('#chat-overlay .space-y-4');
                if (messagesContainer) {
                    const tempMsg = `
                        <div id="${tempMsgId}" class="flex justify-end">
                            <div class="max-w-xs">
                                <div class="bg-[#5C00CC] text-white p-3 rounded-2xl rounded-br-none text-sm opacity-80">
                                    ${message}
                                </div>
                                <p class="text-xs text-gray-500 text-right mt-1">
                                    Mengirim...
                                </p>
                            </div>
                        </div>
                    `;
                    messagesContainer.innerHTML += tempMsg;
                    
                    // Scroll ke bawah
                    const container = document.querySelector('#chat-overlay .overflow-y-auto');
                    if (container) container.scrollTop = container.scrollHeight;
                }
                
                const response = await fetch(`/chat/rooms/${currentRoomId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ pesan: message })
                });
                
                const data = await response.json();
                
                // Hapus pesan loading
                const tempElement = document.getElementById(tempMsgId);
                if (tempElement) tempElement.remove();
                
                if (data.success) {
                    input.value = '';
                    // TAMBAHKAN PESAN BARU KE UI
                    addMessageToUI(data.message);
                } else {
                    alert('Gagal mengirim pesan: ' + (data.error || 'Unknown error'));
                }
                
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Terjadi kesalahan saat mengirim pesan');
                
                // Hapus pesan loading jika error
                const tempElement = document.getElementById(tempMsgId);
                if (tempElement) tempElement.remove();
            }
        }

        // ==================== FUNGSI TAMBAH PESAN KE UI ====================
        function addMessageToUI(messageData) {
            const messagesContainer = document.querySelector('#chat-overlay .space-y-4');
            if (!messagesContainer) return;
            
            if (messagesContainer.innerHTML.includes('Mulai percakapan')) {
                messagesContainer.innerHTML = '';
            }
            
            const isCurrentUser = messageData.sender_id == authUser.id;
            const timeStr = formatTimeFromDB(messageData.created_at); // PAKAI FUNGSI YANG SAMA
            
            const newMessage = `
                <div class="${isCurrentUser ? 'flex justify-end' : 'flex justify-start'}">
                    <div class="max-w-xs">
                        <div class="${isCurrentUser 
                            ? 'bg-[#5C00CC] text-white p-3 rounded-2xl rounded-br-none' 
                            : 'bg-gray-100 text-black p-3 rounded-2xl rounded-bl-none'} text-sm">
                            ${messageData.pesan}
                        </div>
                        <p class="text-xs text-gray-500 ${isCurrentUser ? 'text-right' : ''} mt-1">
                            ${timeStr} • ${isCurrentUser ? 'Terkirim' : 'Dibaca'}
                        </p>
                    </div>
                </div>
            `;
            
            messagesContainer.innerHTML += newMessage;
            
            setTimeout(() => {
                const container = document.querySelector('#chat-overlay .overflow-y-auto');
                if (container) container.scrollTop = container.scrollHeight;
            }, 100);
        }

        // ==================== FUNGSI HELPER ====================
        function isUserLoggedIn() {
            return window.authUser && window.authUser.id;
        }

        function hideChat() {
            const overlay = document.getElementById('chat-overlay');
            const panel = overlay.querySelector('.absolute.inset-y-0');

            panel.style.transform = 'translateX(100%)';

            setTimeout(() => {
                overlay.classList.add('hidden');
                chatVisible = false;
                document.body.style.overflow = '';
            }, 300);
        }

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', () => {
            @auth
                window.authUser = {
                    id: {{ auth()->id() }},
                    name: '{{ auth()->user()->name }}',
                    role: '{{ auth()->user()->role }}',
                    email: '{{ auth()->user()->email }}'
                };
            @endauth
            
            // Enter to send
            const input = document.querySelector('#chat-overlay input');
            if (input) {
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        sendMessage();
                    }
                });
            }
            
            // Tombol send
            const sendBtn = document.querySelector('#chat-overlay button');
            if (sendBtn) {
                sendBtn.addEventListener('click', sendMessage);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && chatVisible) hideChat();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            flatpickr("#tanggalMulai", {
                locale: "id",
                dateFormat: "j F Y",
                minDate: "today",
                disableMobile: "true",
                onChange: function (selectedDates, dateStr) { }
            });

            // Initialize harga
            updateHarga();
        });

        function updateHarga() {
            const select = document.getElementById('periodeSewa');
            const selectedOption = select.options[select.selectedIndex];
            const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;
            const periode = select.value;

            const display = document.getElementById('hargaDisplay');
            const totalDisplay = document.getElementById('totalHargaDisplay');

            if (harga > 0) {
                const formatPeriod = {
                    'bulanan': '/bulan',
                    'harian': '/hari',
                    'mingguan': '/minggu',
                    '3_bulanan': '/3 bulan',
                    '6_bulanan': '/6 bulan',
                    'tahunan': '/tahun'
                };

                const formattedHarga = new Intl.NumberFormat('id-ID').format(harga);
                display.innerHTML = `Rp. ${formattedHarga} <span class="text-xs font-normal text-gray-500">${formatPeriod[periode] || ''}</span>`;
                totalDisplay.innerHTML = `Rp. ${formattedHarga}`;

                // Update hidden input
                document.getElementById('hargaTotalInput').value = harga;
            } else {
                display.innerHTML = 'Harga tidak tersedia';
                totalDisplay.innerHTML = 'Rp. 0';
            }
        }

        document.getElementById('periodeSewa').addEventListener('change', updateHarga);
        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            const tanggalMulai = document.getElementById('tanggalMulai').value;
            const periode = document.getElementById('periodeSewa').value;
            const userGender = "{{ auth()->user()->jenis_kelamin ?? '' }}";
            const kosTipe = "{{ $kos->tipe_kos }}";

            if (!isUserLoggedIn()) {
                alert('Silakan login terlebih dahulu untuk memesan');
                return;
            }

            if (kosTipe !== 'campur') {
                if (kosTipe === 'putra' && userGender !== 'L') {
                    e.preventDefault();
                    alert('Kos ini khusus untuk penghuni PUTRA.');
                    return false;
                }
                if (kosTipe === 'putri' && userGender !== 'P') {
                    e.preventDefault();
                    alert('Kos ini khusus untuk penghuni PUTRI.');
                    return false;
                }
            }
            
            if (!tanggalMulai) {
                e.preventDefault();
                alert('Pilih tanggal mulai kos terlebih dahulu!');
                return false;
            }

            if (!periode) {
                e.preventDefault();
                alert('Pilih periode sewa terlebih dahulu!');
                return false;
            }

            // Format tanggal ke YYYY-MM-DD untuk backend
            const flatpickrInstance = document.querySelector('#tanggalMulai')._flatpickr;
            if (flatpickrInstance) {
                const selectedDate = flatpickrInstance.selectedDates[0];
                if (selectedDate) {
                    const year = selectedDate.getFullYear();
                    const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const day = String(selectedDate.getDate()).padStart(2, '0');
                    const formattedDate = `${year}-${month}-${day}`;
                    document.querySelector('input[name="tanggal_mulai"]').value = formattedDate;
                }
            }

            return true;
        });

        const semuaUlasan = {!! json_encode($semuaUlasan->map(function ($u) {
            return [
                'id' => $u->id,
                'user_name' => $u->user ? $u->user->name : 'Anonymous',
                'rating' => (float) $u->rating,
                'komentar' => $u->komentar ?? '',
                // 'created_at' => $u->created_at ? $u->created_at->toISOString() : null
                'created_at' => $u->created_at ? $u->created_at->toIso8601String() : null
            ];
        })) !!};

        let currentPage = 1;
        const itemsPerPage = 2;
        let filteredUlasan = [...semuaUlasan];

        // Fungsi update tampilan ulasan
        function updateUlasanDisplay() {
            const container = document.getElementById('ulasan-container');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentUlasan = filteredUlasan.slice(startIndex, endIndex);

            // Update HTML
            if (currentUlasan.length > 0) {
                let html = '';
                currentUlasan.forEach(ulasan => {
                    // Samarkan nama
                    const nama = ulasan.user_name;
                    const kata = nama.split(' ');
                    let namaTersamarkan = '';
                    kata.forEach(k => {
                        namaTersamarkan += '*'.repeat(k.length) + ' ';
                    });

                    const date = new Date(ulasan.created_at);
                    const formattedDate = date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });

                    html += `
            <div class="flex gap-3 mb-6">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                    ${nama.charAt(0).toUpperCase()}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between md:grid md:grid-cols-2">
                        <p class="text-xs text-black font-semibold">${namaTersamarkan.trim()}</p>
                        <span class="text-[11px] text-gray-500">★ ${ulasan.rating.toFixed(1)}</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">${formattedDate}</p>
                    <p class="text-xs text-gray-600 mt-1">${ulasan.komentar}</p>
                </div>
            </div>`;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4 md:me-[320px]">Tidak ada ulasan</p>';
            }

            // Update pagination
            updatePagination();
        }

        // Fungsi update pagination controls
        function updatePagination() {
            const totalPages = Math.ceil(filteredUlasan.length / itemsPerPage);
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const currentSpan = document.getElementById('current-page');
            const totalSpan = document.getElementById('total-pages');

            // Update page numbers
            currentSpan.textContent = currentPage;
            totalSpan.textContent = totalPages || 1;

            // Enable/disable buttons
            prevBtn.disabled = currentPage <= 1;
            prevBtn.classList.toggle('btn-disabled', currentPage <= 1);

            nextBtn.disabled = currentPage >= totalPages;
            nextBtn.classList.toggle('btn-disabled', currentPage >= totalPages);
        }

        // Fungsi filter ulasan
        function filterUlasan() {
            const filter = document.getElementById('filterUlasan').value;

            // Reset ke page 1
            currentPage = 1;

            // Apply filter
            switch (filter) {
                case 'terbaru':
                    filteredUlasan = [...semuaUlasan].sort((a, b) =>
                        new Date(b.created_at) - new Date(a.created_at)
                    );
                    break;

                case 'tinggi':
                    filteredUlasan = [...semuaUlasan].sort((a, b) =>
                        b.rating - a.rating
                    );
                    break;

                case 'rendah':
                    filteredUlasan = [...semuaUlasan].sort((a, b) =>
                        a.rating - b.rating
                    );
                    break;

                default:
                    filteredUlasan = [...semuaUlasan];
            }

            updateUlasanDisplay();
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize
            updateUlasanDisplay();

            // Pagination buttons
            document.getElementById('prev-btn').addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateUlasanDisplay();
                }
            });

            document.getElementById('next-btn').addEventListener('click', function () {
                const totalPages = Math.ceil(filteredUlasan.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    updateUlasanDisplay();
                }
            });

            // Filter select
            document.getElementById('filterUlasan').addEventListener('change', filterUlasan);
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Koordinat default (contoh: Yogyakarta)
            const defaultLat = -7.7956;
            const defaultLng = 110.3695;
            const zoomLevel = 15;

            // Inisialisasi map
            const map = L.map('map').setView([defaultLat, defaultLng], zoomLevel);

            // Tambah tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
            }).addTo(map);

            // Jika ada koordinat dari database
            @if($kos->alamatKos)
                const alamatKos = @json($kos->alamatKos);
                const hasCoordinates = alamatKos.lat && alamatKos.lon;

                if (hasCoordinates) {
                    const kosLat = parseFloat(alamatKos.lat);
                    const kosLon = parseFloat(alamatKos.lon);

                    console.log('📍 Kos coordinates:', kosLat, kosLon);

                    // Set view to kos location
                    map.setView([kosLat, kosLon], zoomLevel);

                    // Add marker
                    L.marker([kosLat, kosLon])
                        .addTo(map)
                        .bindPopup(`
                                      <div style="max-width: 250px;">
                                          <h5 style="margin: 0 0 5px 0; color: #d9534f;">
                                              <i class="fas fa-home"></i> {{ $kos->nama_kos }}
                                          </h5>
                                      </div>
                                  `)
                        .openPopup();
                } else {
                    // No coordinates, show default with info
                    L.marker([defaultLat, defaultLon])
                        .addTo(map)
                        .bindPopup('<b>Lokasi Kos</b><br>Koordinat belum tersedia')
                        .openPopup();
                }
            @else
                // No address data at all
                L.marker([defaultLat, defaultLon])
                    .addTo(map)
                    .bindPopup('<b>Informasi Lokasi</b><br>Alamat belum diatur')
                    .openPopup();
            @endif
        });
    </script>
</body>

</html>


<!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== 1. FLATPICKR (TANGGAL) ==========
            const tanggalMulaiInput = document.getElementById('tanggalMulai');
            if (tanggalMulaiInput) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                flatpickr("#tanggalMulai", {
                    locale: "id",
                    dateFormat: "j F Y",
                    minDate: "today",
                    disableMobile: "true",
                    onChange: function (selectedDates, dateStr) { }
                });
            }

            // ========== 2. UPDATE HARGA INIT ==========
            const periodeSelect = document.getElementById('periodeSewa');
            if (periodeSelect) {
                updateHarga();
            }

            // ========== 3. EVENT LISTENER PERIODE ==========
            if (periodeSelect) {
                periodeSelect.addEventListener('change', updateHarga);
            }

            // ========== 4. EVENT LISTENER FORM ==========
            const checkoutForm = document.getElementById('checkoutForm');
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function (e) {
                    const tanggalMulai = document.getElementById('tanggalMulai')?.value;
                    const periode = document.getElementById('periodeSewa')?.value;

                    if (!isUserLoggedIn()) {
                        alert('Silakan login terlebih dahulu untuk memesan');
                        e.preventDefault();
                        return false;
                    }
                    
                    if (!tanggalMulai) {
                        e.preventDefault();
                        alert('Pilih tanggal mulai kos terlebih dahulu!');
                        return false;
                    }

                    if (!periode) {
                        e.preventDefault();
                        alert('Pilih periode sewa terlebih dahulu!');
                        return false;
                    }

                    // Format tanggal ke YYYY-MM-DD untuk backend
                    const flatpickrInstance = document.querySelector('#tanggalMulai')._flatpickr;
                    if (flatpickrInstance) {
                        const selectedDate = flatpickrInstance.selectedDates[0];
                        if (selectedDate) {
                            const year = selectedDate.getFullYear();
                            const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
                            const day = String(selectedDate.getDate()).padStart(2, '0');
                            const formattedDate = `${year}-${month}-${day}`;
                            document.querySelector('input[name="tanggal_mulai"]').value = formattedDate;
                        }
                    }

                    return true;
                });
            }

            // ========== 5. ULASAN FUNCTIONALITY ==========
            const semuaUlasan = {!! json_encode($semuaUlasan->map(function ($u) {
                return [
                    'id' => $u->id,
                    'user_name' => $u->user ? $u->user->name : 'Anonymous',
                    'rating' => (float) $u->rating,
                    'komentar' => $u->komentar ?? '',
                    'created_at' => $u->created_at ? $u->created_at->toIso8601String() : null
                ];
            })) !!};

            let currentPage = 1;
            const itemsPerPage = 2;
            let filteredUlasan = [...semuaUlasan];

            // Fungsi update tampilan ulasan
            function updateUlasanDisplay() {
                const container = document.getElementById('ulasan-container');
                if (!container) return;
                
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const currentUlasan = filteredUlasan.slice(startIndex, endIndex);

                // Update HTML
                if (currentUlasan.length > 0) {
                    let html = '';
                    currentUlasan.forEach(ulasan => {
                        const nama = ulasan.user_name;
                        const kata = nama.split(' ');
                        let namaTersamarkan = '';
                        kata.forEach(k => {
                            namaTersamarkan += '*'.repeat(k.length) + ' ';
                        });

                        const date = new Date(ulasan.created_at);
                        const formattedDate = date.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });

                        html += `
                        <div class="flex gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                ${nama.charAt(0).toUpperCase()}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between md:grid md:grid-cols-2">
                                    <p class="text-xs text-black font-semibold">${namaTersamarkan.trim()}</p>
                                    <span class="text-[11px] text-gray-500">★ ${ulasan.rating.toFixed(1)}</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">${formattedDate}</p>
                                <p class="text-xs text-gray-600 mt-1">${ulasan.komentar}</p>
                            </div>
                        </div>`;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4 md:me-[320px]">Tidak ada ulasan</p>';
                }

                // Update pagination
                updatePagination();
            }

            // Fungsi update pagination controls
            function updatePagination() {
                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');
                const currentSpan = document.getElementById('current-page');
                const totalSpan = document.getElementById('total-pages');
                
                if (!prevBtn || !nextBtn || !currentSpan || !totalSpan) return;

                const totalPages = Math.ceil(filteredUlasan.length / itemsPerPage);
                
                currentSpan.textContent = currentPage;
                totalSpan.textContent = totalPages || 1;

                prevBtn.disabled = currentPage <= 1;
                prevBtn.classList.toggle('btn-disabled', currentPage <= 1);

                nextBtn.disabled = currentPage >= totalPages;
                nextBtn.classList.toggle('btn-disabled', currentPage >= totalPages);
            }

            // Fungsi filter ulasan
            function filterUlasan() {
                const filterSelect = document.getElementById('filterUlasan');
                if (!filterSelect) return;
                
                const filter = filterSelect.value;
                currentPage = 1;

                switch (filter) {
                    case 'terbaru':
                        filteredUlasan = [...semuaUlasan].sort((a, b) =>
                            new Date(b.created_at) - new Date(a.created_at)
                        );
                        break;
                    case 'tinggi':
                        filteredUlasan = [...semuaUlasan].sort((a, b) =>
                            b.rating - a.rating
                        );
                        break;
                    case 'rendah':
                        filteredUlasan = [...semuaUlasan].sort((a, b) =>
                            a.rating - b.rating
                        );
                        break;
                    default:
                        filteredUlasan = [...semuaUlasan];
                }

                updateUlasanDisplay();
            }

            // Initialize ulasan
            updateUlasanDisplay();

            // Pagination buttons
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        updateUlasanDisplay();
                    }
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    const totalPages = Math.ceil(filteredUlasan.length / itemsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateUlasanDisplay();
                    }
                });
            }

            // Filter select
            const filterUlasanSelect = document.getElementById('filterUlasan');
            if (filterUlasanSelect) {
                filterUlasanSelect.addEventListener('change', filterUlasan);
            }

            // ========== 6. MAP FUNCTIONALITY ==========
            const mapElement = document.getElementById('map');
            if (mapElement) {
                const defaultLat = -7.7956;
                const defaultLng = 110.3695;
                const zoomLevel = 15;

                const map = L.map('map').setView([defaultLat, defaultLng], zoomLevel);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
                }).addTo(map);

                @if($kos->alamatKos)
                    const alamatKos = @json($kos->alamatKos);
                    const hasCoordinates = alamatKos.lat && alamatKos.lon;

                    if (hasCoordinates) {
                        const kosLat = parseFloat(alamatKos.lat);
                        const kosLon = parseFloat(alamatKos.lon);

                        map.setView([kosLat, kosLon], zoomLevel);

                        L.marker([kosLat, kosLon])
                            .addTo(map)
                            .bindPopup(`
                                <div style="max-width: 250px;">
                                    <h5 style="margin: 0 0 5px 0; color: #d9534f;">
                                        <i class="fas fa-home"></i> {{ $kos->nama_kos }}
                                    </h5>
                                </div>
                            `)
                            .openPopup();
                    } else {
                        L.marker([defaultLat, defaultLng])
                            .addTo(map)
                            .bindPopup('<b>Lokasi Kos</b><br>Koordinat belum tersedia')
                            .openPopup();
                    }
                @else
                    L.marker([defaultLat, defaultLng])
                        .addTo(map)
                        .bindPopup('<b>Informasi Lokasi</b><br>Alamat belum diatur')
                        .openPopup();
                @endif
            }
        });

        // ========== 7. UPDATE HARGA FUNCTION ==========
        function updateHarga() {
            const select = document.getElementById('periodeSewa');
            if (!select) return;
            
            const selectedOption = select.options[select.selectedIndex];
            const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;
            const periode = select.value;

            const display = document.getElementById('hargaDisplay');
            const totalDisplay = document.getElementById('totalHargaDisplay');

            if (!display || !totalDisplay) return;

            if (harga > 0) {
                const formatPeriod = {
                    'bulanan': '/bulan',
                    'harian': '/hari',
                    'mingguan': '/minggu',
                    '3_bulanan': '/3 bulan',
                    '6_bulanan': '/6 bulan',
                    'tahunan': '/tahun'
                };

                const formattedHarga = new Intl.NumberFormat('id-ID').format(harga);
                display.innerHTML = `Rp. ${formattedHarga} <span class="text-xs font-normal text-gray-500">${formatPeriod[periode] || ''}</span>`;
                totalDisplay.innerHTML = `Rp. ${formattedHarga}`;

                const hargaTotalInput = document.getElementById('hargaTotalInput');
                if (hargaTotalInput) {
                    hargaTotalInput.value = harga;
                }
            } else {
                display.innerHTML = 'Harga tidak tersedia';
                totalDisplay.innerHTML = 'Rp. 0';
            }
        }
    </script> -->