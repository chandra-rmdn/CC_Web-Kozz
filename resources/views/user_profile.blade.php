<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Koszzz - Temukan kos favoritmu</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                <button class="bg-[#5C00CC] text-white w-36 h-[30px] text-sm font-medium rounded-[12px]">Profil
                    Saya</button>
            </a>
            <a href="{{ route('my.booking') }}">
                <button class="bg-gray-300 text-white w-36 h-[30px] text-sm font-medium rounded-[12px]">Booking
                    Saya</button>
            </a>
        </div>
        <div class="flex gap-12">
            <!-- Sidebar teks -->
            <div class="hidden md:block w-32 pt-8">
                <a href="{{ route('user.profile') }}"
                    class="text-md font-poppins font-semibold text-[#5C00CC] mb-6 block">Profil Saya</a>
                <a href="{{ route('my.booking') }}"
                    class="text-md font-poppins font-semibold text-black mb-3 block">Booking Saya</a>
            </div>

            <!-- Box putih -->
            <section
                class="bg-white flex-1 min-h-[calc(100vh-100px)] rounded-t-[20px] shadow-sm px-4 md:px-8 py-6 md:pt-[38px]">
                <div class="md:px-[58px] md:pt-1">
                    <!-- Title -->
                    <h1 class="text-lg text-black md:text-xl font-semibold mb-3">My Profile Saya</h1>
                    <p class="text-sm text-black md:text-[15px] mb-8">{{ auth()->user()->email }}</p>
                    <div class="mb-4">
                        <p class="text-md text-black md:text-[15px] font-semibold mb-2">Nama Lengkap</p>
                        <p class="text-sm text-black md:text-[15px]">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-md text-black md:text-[15px] font-semibold mb-2">Nomor Telepon</p>
                        <p class="text-sm text-black md:text-[15px]">{{ auth()->user()->no_hp }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-md text-black md:text-[15px] font-semibold mb-2">Jenis Kelamin</p>
                        <p class="text-sm text-black md:text-[15px]">
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
            </section>
        </div>
    </main>
    <script src="{{ asset('js/flyonui.js') }}"></script>
</body>

</html>