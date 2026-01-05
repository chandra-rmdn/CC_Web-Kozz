<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Koszzz - Temukan kos favoritmu</title>
    <link href="../src/output.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>

<body class="bg-[#F6F5FB]">
    <!-- Navbar -->
    <header class="w-full flex items-center justify-between px-4 md:px-[88px] py-[14px] bg-[#F6F5FB]">
        <div class="flex items-center gap-[7px]">
            <div class="icon-home text-black"><i class="bi bi-houses-fill text-2xl md:text-[34px]"></i></div>
            <span class="text-2xl md:text-[32px] font-poppins font-bold text-[#5C00CC]">Koszzz</span>
        </div>

        <div class="flex items-center gap-[12px]">
            <button
                class="btn btn-text rounded-full font-semibold shadow-none text-[14px] sm:text-[14px] hover:bg-[#DCDCDC]">
                <span class="text-black hidden sm:inline">Menjadi Pemilik Kos</span>
                <span class="text-black sm:hidden">Pemilik</span>
            </button>
            <a href="{{ route('user.profile') }}">
                <button class="btn btn-circle shadow-none" style="--btn-color:#DCDCDC">A</button>
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
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Hero + Search -->
    <main class="px-2 md:px-[61px] pt-[25px]">
        <section class="text-center mb-[33px]">
            <h1 class="text-black text-xl md:text-[34px] font-bold mb-[28px]">Temukan kos favoritmu, anjay</h1>
            <div
                class="max-w-[597px] h-[50px] px-[20px] mx-auto flex items-center bg-white rounded-[20px] shadow-md overflow-hidden">
                <input type="text" placeholder="Masukkan nama koszzz" class="flex-1 text-[14px] outline-none" />
                <button
                    class="bg-[#5C00CC] text-white w-[80px] h-[30px] text-sm font-medium rounded-[12px]">Cari</button>
            </div>
        </section>

        <!-- Section Kos Putra -->
        <section class="bg-white rounded-t-[20px] shadow-sm px-4 md:px-[102px] pt-8 md:pt-[41px]">
            <div class="flex items-center justify-between mb-2 md:mb-[28px]">
                <h2 class="text-black font-semibold text-[18px]">Kos Putra</h2>
                <a href="#" class="text-sm text-gray-500 link link-animated">Lihat semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-[25px]">
                <!-- Card -->
                <a href="detail_koz.html" class="block">
                    <article class="rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3">
                            <img src="./walpaper_windows.jpeg" alt="kos maguwo city"
                                class="w-full h-full object-cover rounded-[20px]" />
                        </div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Maguwo City</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 750.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>Sisa 1 kamar</span>
                            <span>★ 4.94</span>
                        </div>
                    </article>
                </a>

                <a href="detail_koz.html" class="block">
                    <article class=" rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3"></div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Pak Buah Lil</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 2.000.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>Sisa 1 kamar</span>
                            <span>★ 5.0</span>
                        </div>
                    </article>
                </a>

                <a href="#" class="block">
                    <article class=" rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3"></div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Bu Diman</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 800.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>&nbsp;</span>
                            <span>★ 5.0</span>
                        </div>
                    </article>
                </a>

                <a href="#" class="block">
                    <article class=" rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3"></div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Pak 3 Squad</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 600.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>&nbsp;</span>
                            <span>★ 5.0</span>
                        </div>
                    </article>
                </a>
            </div>

            <!-- Kos Putri label -->
            <div class="flex items-center justify-between mb-2 mt-10 md:mb-[28px]">
                <h2 class="text-black font-semibold text-[18px]">Kos Putri</h2>
                <a href="#" class="text-sm text-gray-500 link link-animated">Lihat semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-[25px]">
                <!-- Card -->
                <a href="#" class="block">
                    <article class="rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3"></div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Maguwo City</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 750.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>Sisa 1 kamar</span>
                            <span>★ 4.94</span>
                        </div>
                    </article>
                </a>

                <a href="#" class="block">
                    <article class=" rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3"></div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Pak Buah Lil</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 2.000.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>Sisa 1 kamar</span>
                            <span>★ 5.0</span>
                        </div>
                    </article>
                </a>

                <a href="#" class="block">
                    <article class=" rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3"></div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Bu Diman</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 800.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>&nbsp;</span>
                            <span>★ 5.0</span>
                        </div>
                    </article>
                </a>

                <a href="#" class="block">
                    <article class=" rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3"></div>
                        <h3 class="text-black text-sm font-semibold mb-1">Kos Pak 3 Squad</h3>
                        <p class="text-xs text-gray-600 mb-1">Rp. 600.000/bulan</p>
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            <span>&nbsp;</span>
                            <span>★ 5.0</span>
                        </div>
                    </article>
                </a>
            </div>
            <div class="border-t border-gray-300 mt-10"></div>
        </section>

        <!-- Footer -->
        <footer class="footer bg-white flex flex-col items-center gap-4 pt-10 pb-6 shadow-md">
            <div class="flex gap-1 text-xl font-bold text-base-content">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="22" fill="currentColor"
                    class="bi bi-houses-fill" viewBox="0 0 14 14">
                    <path
                        d="M7.207 1a1 1 0 0 0-1.414 0L.146 6.646a.5.5 0 0 0 .708.708L1 7.207V12.5A1.5 1.5 0 0 0 2.5 14h.55a2.5 2.5 0 0 1-.05-.5V9.415a1.5 1.5 0 0 1-.56-2.475l5.353-5.354z" />
                    <path
                        d="M8.793 2a1 1 0 0 1 1.414 0L12 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l1.854 1.853a.5.5 0 0 1-.708.708L15 8.207V13.5a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 13.5V8.207l-.146.147a.5.5 0 1 1-.708-.708z" />
                </svg>
                <span>Koszzz</span>
            </div>
            <aside class="md:text-sm">
                <p>©2025<span>Koszzz</span></p>
            </aside>
            <nav class="grid-flow-col gap-4">
                <a class="md:text-sm link link-hover text-base-content" href="#">License</a>
                <a class="md:text-sm link link-hover text-base-content" href="#">Help</a>
                <a class="md:text-sm link link-hover text-base-content" href="#">Contact</a>
                <a class="md:text-sm link link-hover text-base-content" href="#">Policy</a>
            </nav>
            <div class="flex h-5 gap-4">
                <a href="#" class="link text-base-content" aria-label="Github Link">
                    <span class="icon-[tabler--brand-github-filled] size-5"></span>
                </a>
                <a href="#" class="link text-base-content" aria-label="Facebook Link">
                    <span class="icon-[tabler--brand-facebook-filled] size-5"></span>
                </a>
                <a href="#" class="link text-base-content" aria-label="X Link">
                    <span class="icon-[tabler--brand-x-filled] size-5"></span>
                </a>
                <a href="#" class="link text-base-content" aria-label="Google Link">
                    <span class="icon-[tabler--brand-google-filled] size-5"></span>
                </a>
            </div>
        </footer>
    </main>
    <script src="{{ asset('js/flyonui.js') }}"></script>
</body>

</html>