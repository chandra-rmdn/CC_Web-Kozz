<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Koszzz</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
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

  <!-- Content wrapper -->
  <main class="px-2 md:px-[61px] pt-[25px] min-h-[calc(100vh-75px)]">
    <section class="bg-white min-h-[calc(100vh-100px)] rounded-t-[20px] shadow-sm px-4 md:px-8 py-6 md:pt-[38px]">
      <a href="/checkout">
        <button class="flex items-center gap-1 text-xs text-gray-600 mb-4">
          <span>&lt;</span>
          <span>Kembali</span>
        </button>
      </a>
      <div class="md:px-[62px] md:pt-1">
        <!-- Title -->
        <h1 class="text-lg text-black md:text-xl font-semibold mb-6">Pembayaran</h1>

        <!-- Steps -->
        <div class="flex items-center gap-6 text-[11px] text-gray-500 mb-7">
          <div class="flex flex-col items-center gap-1">
            <span class="w-13 h-[6px] rounded-full bg-[#5C00CC]"></span>
            <span>Ajukan sewa</span>
          </div>
          <div class="flex flex-col items-center gap-1">
            <span class="w-13 h-[6px] rounded-full bg-[#5C00CC]"></span>
            <span>Pemilik setuju</span>
          </div>
          <div class="flex flex-col items-center gap-1">
            <span class="w-13 h-[6px] rounded-full bg-[#5C00CC]"></span>
            <span>Bayar sewa</span>
          </div>
          <div class="flex flex-col items-center gap-1">
            <span class="w-13 h-[6px] rounded-full bg-gray-200"></span>
            <span>Check-in</span>
          </div>
        </div>

        <!-- Card pesanan -->
        <div class="border-2 border-gray-200 rounded-[20px] px-6 py-6 max-w-xl w-full">
          <div class="flex justify-between gap-4">
            <!-- Left info -->
            <div class="space-y-3 text-xs">
              <div>
                <p class="text-sm text-black font-semibold">Kos Pak Buah Lil</p>
                <span
                  class="text-black inline-block mt-2 px-2 py-[2px] rounded-full border border-gray-400 text-[10px]">
                  Kos Putra </span>
              </div>

              <div class="flex gap-2 mt-4">
                <span class="text-gray-500 w-20">Mulai Sewa:</span>
                <span class="font-medium text-black hidden md:inline">3 September 2025</span>
                <span class="font-medium text-black md:hidden">3 Sep 2025</span>
              </div>
              <div class="flex gap-2">
                <span class="text-gray-500 w-20">Lama Sewa:</span>
                <span class="font-medium text-black">1 Bulan</span>
              </div>

              <span class="text-gray-500 w-20">Total Bayar:</span>
              <span class="flex mt-1 font-medium text-[14px] text-black">Rp 2.000.000</span>

              <div
                class="mt-3 inline-flex px-2 py-1 rounded-[6px] bg-[#EDF9F4] text-[10px] text-[#016034] border-2 border-[#AADEC6] font-medium">
                Disetujui</div>
            </div>

            <!-- Right image -->
            <div class="flex w-30 h-26 rounded-2xl border border-gray-200 items-center justify-center">
              <img src="./walpaper_windows.jpeg" class="w-full h-full object-cover rounded-2xl" />
            </div>
          </div>

          <div class="mt-4 flex justify-center">
            <button class="btn w-40 h-9 text-white text-xs font-medium rounded-full bg-[#5C00CC]">Bayar
              Sekarang</button>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script src="{{ asset('js/flyonui.js') }}"></script>
</body>

</html>