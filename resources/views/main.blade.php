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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .modal-dialog {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            transform: translateY(-20px);
            transition: transform 0.3s;
        }

        .overlay.open .modal-dialog {
            transform: translateY(0);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.5rem;
            line-height: 1;
        }

        .tab-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem 0;
            position: relative;
        }

        .tab-btn.show {
            color: #5C00CC;
            font-weight: 600;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.show {
            display: block;
        }

        .switch-tab-btn {
            background: none;
            border: none;
            cursor: pointer;
            text-decoration: underline;
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
    <!-- Navbar -->
    <header class="w-full flex items-center justify-between px-4 md:px-[88px] py-[14px] bg-[#F6F5FB]">
        <div class="flex items-center gap-[7px]">
            <div class="icon-home text-black"><i class="bi bi-houses-fill text-2xl md:text-[34px]"></i></div>
            <span class="text-2xl md:text-[32px] font-poppins font-bold text-[#5C00CC]">Koszzz</span>
        </div>
        @auth
            <div class="flex items-center gap-[12px]">
                <button onclick="showChat()"
                    class="btn btn-outline btn-xs text-xs me-4 rounded-full text-[#5C00CC] border-[#5C00CC] hover:bg-[#5C00CC] hover:text-white">
                    Chat
                </button>

                <!-- OVERLAY -->
                <div id="chat-overlay" class="fixed inset-0 z-50 hidden">
                    <div id="chat-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="hideChat()">
                    </div>

                    <div
                        class="absolute inset-y-0 right-0 w-96 bg-white shadow-2xl transform transition-all duration-300 ease-out">
                        <!-- HEADER -->
                        <div class="flex items-center justify-between p-4 border-b">
                            <div class="flex items-center">
                                <!-- Back button untuk detail chat (tersembunyi di view daftar) -->
                                <button id="back-to-list" class="mr-3 p-1 w-8 h-8 hidden" onclick="showChatList()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                                        <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="42" d="M244 400L100 256l144-144M120 256h292" />
                                    </svg>
                                </button>
                                <p id="chat-title" class="text-2xl font-bold text-black">Chat</p>
                            </div>
                            <button onclick="hideChat()" class="p-2 hover:bg-gray-100 rounded-full">
                                <i class="fas fa-times text-gray-600"></i>
                            </button>
                        </div>

                        <!-- DAFTAR CHAT -->
                        <div id="chat-list" class="p-4 h-[calc(100vh-140px)] overflow-y-auto">
                            <div class="space-y-4">

                                <hr class="my-2 border-gray-300 ms-14">

                            </div>
                        </div>

                        <!-- DETAIL CHAT -->
                        <div id="chat-detail" class="hidden h-[calc(100vh-140px)] flex flex-col">
                            <!-- HEADER -->
                            <div class="flex items-center justify-between p-4 border-b">
                                <div class="flex items-center">
                                    <div id="chat-avatar"
                                        class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 mr-3">
                                    </div>
                                    <div>
                                        <p id="chat-contact-name" class="font-semibold text-black text-sm"></p>
                                        <p id="chat-contact-role" class="text-xs text-gray-500"></p>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 30 32">
                                            <path fill="#000"
                                                d="M2.078 3.965c-.407-1.265.91-2.395 2.099-1.801l24.994 12.495c1.106.553 1.106 2.13 0 2.684L4.177 29.838c-1.188.594-2.506-.536-2.099-1.801L5.95 16.001zm5.65 13.036L4.347 27.517l23.037-11.516L4.346 4.485L7.73 15H19a1 1 0 1 1 0 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


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
        @else
            <div class="flex items-center gap-[12px]">
                <button id="open-modal-btn" data-open-modal="login" data-role="pemilik"
                    class="btn btn-text rounded-full font-semibold shadow-none text-[14px] sm:text-[14px] hover:bg-[#DCDCDC]">
                    <span class="text-black hidden sm:inline">Menjadi Pemilik Kos</span>
                    <span class="text-black sm:hidden">Pemilik</span>
                </button>
                <a href="{{ route('user.profile') }}" class="hidden">
                    <button class="btn btn-circle shadow-none" style="--btn-color:#DCDCDC">A</button>
                </a>

                <div class="dropdown relative inline-flex">
                    <button id="dropdown-menu-icon" type="button" class="dropdown-toggle btn btn-circle shadow-none"
                        style="--btn-color:#DCDCDC" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                        <i class="text-black bi bi-list"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-open:opacity-100 bg-white shadow-md hidden min-w-50" role="menu"
                        aria-orientation="vertical" aria-labelledby="dropdown-menu-icon">
                        <li>
                            <button data-open-modal="login" data-role="penyewa" type="button"
                                class="open-modal-penyewa dropdown-item text-black gap-1 text-[15px] w-full text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Masuk atau Daftar
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </header>

    <div id="login-modal" data-default-role="penyewa" class="overlay" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    <h3 class="modal-title text-black">Login atau Daftar</h3>
                </div>

                <div class="modal-body">
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- TAB 1: LOGIN -->
                        <div id="tab-login" class="tab-pane show">
                            <div class="space-y-3">
                                <button class="w-full btn btn-outline border-gray-400 text-gray-500 hover:bg-gray-100">
                                    <i class="bi bi-google mr-2"></i>Login dengan Google
                                </button>
                            </div>
                            <div class="relative mt-6 mb-4">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-3 bg-white text-gray-500 text-xs">Atau lanjutkan dengan</span>
                                </div>
                            </div>

                            <!-- Form Login -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="hidden" id="login_role" name="role" value="penyewa">
                                @if($errors->has('email') || $errors->has('password'))
                                    <div
                                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                                        @error('email')
                                            <p>✗ {{ $message }}</p>
                                        @enderror
                                        @error('password')
                                            <p>✗ {{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                <div class="form-control w-full">
                                    <label class="label-text text-black" for="login-email">Email</label>
                                    <div class="input text-black bg-white border-gray-400">
                                        <input type="email" name="email" placeholder="Email" id="login-email"
                                            required />
                                    </div>

                                    <label class="label-text text-black mt-4" for="login-password">Password</label>
                                    <div class="input mb-4 text-black bg-white border-gray-400">
                                        <input id="login-password" type="password" name="password"
                                            placeholder="Password" required />
                                        <button type="button" data-toggle-password='{ "target": "#login-password" }'
                                            class="block cursor-pointer" aria-label="password toggle">
                                            <span
                                                class="icon-[tabler--eye] text-gray-400 password-active:block hidden size-5"></span>
                                            <span
                                                class="icon-[tabler--eye-off] text-gray-400 password-active:hidden block size-5"></span>
                                        </button>
                                    </div>

                                    <!-- Lupa password link -->
                                    <div class="text-right mb-6">
                                        <button class="text-xs text-purple-600 hover:underline switch-tab-btn"
                                            data-tab="reset">Lupa password?</button>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit"
                                            class="bg-[#5C00CC] text-white w-full h-10 text-sm font-medium rounded-lg">
                                            Masuk
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="text-xs mt-4">
                                <span class="text-gray-500">Belum punya akun?</span>
                                <button class="text-purple-600 font-medium ml-1 switch-tab-btn"
                                    data-tab="daftar">Daftar</button>
                            </div>
                        </div>

                        <!-- TAB 2: DAFTAR -->
                        <div id="tab-daftar" class="tab-pane">
                            <!-- Form Daftar -->
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <input type="hidden" id="register_role" name="role" value="penyewa">
                                @if($errors->any())
                                    <div
                                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                                        <ul class="list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>✗ {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="form-control w-full">
                                    <label class="label-text text-black" for="daftar-nama">Nama Lengkap</label>
                                    <div class="input text-black bg-white border-gray-400 mb-4">
                                        <input type="text" name="name" placeholder="Nama lengkap" id="daftar-nama"
                                            required />
                                    </div>

                                    <label class="label-text text-black" for="daftar-email">Email</label>
                                    <div class="input text-black bg-white border-gray-400 mb-4">
                                        <input type="email" name="email" placeholder="Email" id="daftar-email"
                                            required />
                                    </div>

                                    <label class="label-text text-black" for="daftar-no_hp">Nomor HP</label>
                                    <div class="input text-black bg-white border-gray-400 mb-4">
                                        <input type="text" name="no_hp" placeholder="Nomor HP" id="daftar-no_hp"
                                            required />
                                    </div>

                                    <label class="label-text text-black" for="daftar-jenis_kelamin">Kelamin</label>
                                    <select class="select bg-white text-black border-gray-400 mb-4"
                                        id="daftar-jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>

                                    <label class="label-text text-black" for="daftar-password">Password</label>
                                    <div class="input mb-4 text-black bg-white border-gray-400">
                                        <input id="daftar-password" type="password" name="password"
                                            placeholder="Password" required />
                                        <button type="button" data-toggle-password='{ "target": "#daftar-password" }'
                                            class="block cursor-pointer" aria-label="password toggle">
                                            <span
                                                class="icon-[tabler--eye] text-gray-400 password-active:block hidden size-5"></span>
                                            <span
                                                class="icon-[tabler--eye-off] text-gray-400 password-active:hidden block size-5"></span>
                                        </button>
                                    </div>

                                    <label class="label-text text-black" for="daftar-confirm-password">Konfirmasi
                                        Password</label>
                                    <div class="input mb-8 text-black bg-white border-gray-400">
                                        <input id="daftar-confirm-password" type="password" name="password_confirmation"
                                            placeholder="Ulangi password" required />
                                        <button type="button"
                                            data-toggle-password='{ "target": "#daftar-confirm-password" }'
                                            class="block cursor-pointer" aria-label="password toggle">
                                            <span
                                                class="icon-[tabler--eye] text-gray-400 password-active:block hidden size-5"></span>
                                            <span
                                                class="icon-[tabler--eye-off] text-gray-400 password-active:hidden block size-5"></span>
                                        </button>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit"
                                            class="bg-[#5C00CC] text-white w-full h-10 text-sm font-medium rounded-lg">
                                            Daftar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="text-xs mt-4">
                                <span class="text-gray-500">Sudah punya akun?</span>
                                <button class="text-purple-600 font-medium ml-1 switch-tab-btn"
                                    data-tab="login">Login</button>
                            </div>
                        </div>

                        <!-- TAB 3: RESET PASSWORD -->
                        <div id="tab-reset" class="tab-pane">
                            <form id="resetPasswordForm" method="POST">
                                @csrf
                                <!-- @if(session('status'))
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                                        {{ session('status') }}
                                    </div>
                                @endif -->
                                <div id="resetMessages"></div>

                                <div class="form-control w-full">
                                    <label class="label-text text-black" for="reset-email">Email</label>
                                    <div class="input text-black bg-white border-gray-400 mb-4">
                                        <input type="email" name="email" placeholder="Email Anda" id="reset-email"
                                            required value="" />
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" id="resetSubmitBtn"
                                            class="bg-[#5C00CC] text-white w-full h-10 text-sm font-medium rounded-lg">
                                            <span id="resetBtnText">Kirim Link Reset</span>
                                            <div id="resetSpinner" class="hidden">
                                                <!-- Loading Spinner -->
                                                <svg class="animate-spin h-5 w-5 text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div id="resetResult" class="hidden mt-6">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center gap-2 text-green-800 mb-2">
                                        <span class="icon-[tabler--circle-check] size-5"></span>
                                        <p class="font-medium">Link reset password berhasil dibuat!</p>
                                    </div>

                                    <p class="text-gray-600 text-sm mt-2">Salin link berikut dan buka di browser:</p>

                                    <!-- Link Display -->
                                    <div class="flex items-center gap-2 mt-3">
                                        <span class="text-gray-700 whitespace-nowrap text-sm">Link Reset:</span>
                                        <div class="flex-1 relative">
                                            <input type="text" id="resetLinkDisplay" readonly class="w-full p-2 pr-10 border border-gray-300 rounded text-sm
                                                    bg-gray-50 font-mono text-gray-700 focus:outline-none">
                                            <button type="button" id="copyResetLinkBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2
                                                    text-gray-500 hover:text-gray-700 transition"
                                                data-tooltip="Copy link">
                                                <span id="copyIcon" class="icon-[tabler--clipboard] size-5"></span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Expiry Info -->
                                    <div class="flex items-center gap-2 mt-3 text-xs text-gray-500">
                                        <span class="icon-[tabler--clock] size-4"></span>
                                        <span>Link berlaku hingga <span id="expiryTime"></span> (60 menit)</span>
                                    </div>

                                    <!-- Open Link Button -->
                                    <div class="mt-4">
                                        <button type="button" id="openResetLinkBtn"
                                            class="w-full bg-[#5C00CC] text-white py-2 px-4 
                                                rounded text-sm font-medium transition flex items-center justify-center gap-2">
                                            <span class="icon-[tabler--external-link] size-4"></span>
                                            Buka Link di Tab Baru
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-xs mt-4">
                                <span class="text-gray-500">Ingat password?</span>
                                <button class="text-purple-600 font-medium ml-1 switch-tab-btn"
                                    data-tab="login">Login</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero + Search -->
    <main class="px-2 md:px-[61px] pt-[25px]">
        <section class="text-center text-black mb-[33px]">
            <h1 class="text-black text-xl md:text-[34px] font-bold mb-[28px]">Temukan kos favoritmu, anjay</h1>
            <form action="{{ route('kos.search') }}" method="GET"
                class="max-w-[597px] h-[50px] px-[20px] mx-auto flex items-center bg-white rounded-[20px] shadow-md overflow-hidden">
                <input type="text" name="q" placeholder="Masukkan nama koszzz" class="flex-1 text-[14px] outline-none"
                    value="{{ request('q') }}" />
                <button
                    class="bg-[#5C00CC] text-white w-[80px] h-[30px] text-sm font-medium rounded-[12px]">Cari</button>
            </form>
        </section>

        <!-- Section Kos Putra -->
        <section class="bg-white rounded-t-[20px] shadow-sm px-4 md:px-[102px] pt-8 md:pt-[41px]">
            <div class="flex items-center justify-between mb-4 md:mb-[28px]">
                <h2 class="text-black font-semibold text-[18px]">Kos Putra</h2>
                <a href="{{ route('kos.search', ['tipe' => 'putra']) }}"
                    class="text-sm text-gray-500 link link-animated">
                    Lihat semua
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-[25px]">
                @forelse($kosPutra as $kos)
                    <!-- Card -->
                    <a href="{{ route('detail.kos', $kos->id) }}" class="block">
                        <article class="rounded-[20px]">
                            <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] border border-gray-300 mb-3">
                                @if($kos->fotoKos->count() > 0)
                                    @php
                                        $foto = $kos->fotoKos->first();
                                    @endphp
                                    @if(str_starts_with($foto->path_foto, '/9j/') || str_starts_with($foto->path_foto, 'data:image'))
                                        <img src="data:image/jpeg;base64,{{ $foto->path_foto }}" alt="{{ $kos->nama_kos }}"
                                            class="w-full h-full object-cover rounded-[20px]" />
                                    @endif
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-100 to-blue-100 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                flex items-center justify-center rounded-[20px]">
                                        <i class="bi bi-house text-[#5C00CC] text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center text-[11px] mb-1">
                                <h3 class="text-black text-sm font-semibold">{{ $kos->nama_kos }}</h3>
                                <span class="text-gray-500">★ {{ number_format($kos->mean_rating, 1) }}</span>
                            </div>

                            @php
                                $hargaTerendah = null;
                                foreach ($kos->kamar as $kamar) {
                                    if ($kamar->hargaSewa->count() > 0) {
                                        $harga = $kamar->hargaSewa->first()->harga;
                                        if ($hargaTerendah === null || $harga < $hargaTerendah) {
                                            $hargaTerendah = $harga;
                                        }
                                    }
                                }
                            @endphp
                            <p class="text-xs text-gray-600 mb-1">
                                @if($hargaTerendah)
                                    Rp. {{ number_format($hargaTerendah, 0, ',', '.') }}/bulan
                                @else
                                    Harga mulai dari...
                                @endif
                            </p>
                            <div class="flex justify-between items-center text-[11px] text-gray-500">
                                @if($kos->kamar_tersedia == 0)
                                    <span class="text-red-500 font-medium">Penuh</span>
                                @elseif($kos->kamar_tersedia <= 3)
                                    <span>Sisa {{ $kos->kamar_tersedia }} kamar</span>
                                @else
                                    <span>{{ $kos->kamar_tersedia }} Kamar tersedia</span>
                                @endif
                            </div>
                        </article>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-8 text-gray-500">
                        <p>Belum ada data kos putra</p>
                    </div>
                @endforelse
            </div>

            <!-- Kos Putri label -->
            <div class="flex items-center justify-between mb-4 mt-16 md:mb-[28px]">
                <h2 class="text-black font-semibold text-[18px]">Kos Putri</h2>
                <a href="{{ route('kos.search', ['tipe' => 'putri']) }}"
                    class="text-sm text-gray-500 link link-animated">
                    Lihat semua
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-[25px]">
                @forelse($kosPutri as $kos)
                    <!-- Card -->
                    <a href="{{ route('detail.kos', $kos->id) }}" class="block">
                        <article class="rounded-[20px]">
                            <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] border border-gray-300 mb-3">
                                @if($kos->fotoKos->count() > 0)
                                    @php
                                        $foto = $kos->fotoKos->first();
                                    @endphp
                                    @if(str_starts_with($foto->path_foto, '/9j/') || str_starts_with($foto->path_foto, 'data:image'))
                                        <img src="data:image/jpeg;base64,{{ $foto->path_foto }}" alt="{{ $kos->nama_kos }}"
                                            class="w-full h-full object-cover rounded-[20px]" />
                                    @endif
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-100 to-blue-100 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                flex items-center justify-center rounded-[20px]">
                                        <i class="bi bi-house text-[#5C00CC] text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <h3 class="text-black text-sm font-semibold mb-1">{{ $kos->nama_kos }}</h3>
                                <span class="text-gray-500">★ {{ number_format($kos->mean_rating, 1) }}</span>
                            </div>

                            @php
                                $hargaTerendah = null;
                                foreach ($kos->kamar as $kamar) {
                                    if ($kamar->status != 'terisi' && $kamar->hargaSewa->count() > 0) {
                                        $harga = $kamar->hargaSewa->first()->harga;
                                        if ($hargaTerendah === null || $harga < $hargaTerendah) {
                                            $hargaTerendah = $harga;
                                        }
                                    }
                                }
                            @endphp
                            <p class="text-xs text-gray-600 mb-1">
                                @if($hargaTerendah)
                                    Rp. {{ number_format($hargaTerendah, 0, ',', '.') }}/bulan
                                @else
                                    Harga mulai dari...
                                @endif
                            </p>
                            <div class="flex justify-between items-center text-[11px] text-gray-500">
                                @if($kos->kamar_tersedia > 3)
                                    <span></span>
                                @elseif($kos->kamar_tersedia <= 3)
                                    <span>Sisa {{ $kos->kamar_tersedia }} kamar</span>
                                @else
                                    <span class="text-red-500 font-medium">Penuh</span>
                                @endif
                            </div>
                        </article>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-8 text-gray-500">
                        <p>Belum ada data kos putri</p>
                    </div>
                @endforelse
            </div>

            <!-- Kos Campur label -->
            <div class="flex items-center justify-between mb-4 mt-16 md:mb-[28px]">
                <h2 class="text-black font-semibold text-[18px]">Kos Campur</h2>
                <a href="{{ route('kos.search', ['tipe' => 'campur']) }}"
                    class="text-sm text-gray-500 link link-animated">
                    Lihat semua
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-[25px]">
                @forelse($kosCampur as $kos)
                    <!-- Card -->
                    <a href="{{ route('detail.kos', $kos->id) }}" class="block">
                        <article class="rounded-[20px]">
                            <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] border border-gray-300 mb-3">
                                @if($kos->fotoKos->count() > 0)
                                    @php
                                        $foto = $kos->fotoKos->first();
                                    @endphp
                                    @if(str_starts_with($foto->path_foto, '/9j/') || str_starts_with($foto->path_foto, 'data:image'))
                                        <img src="data:image/jpeg;base64,{{ $foto->path_foto }}" alt="{{ $kos->nama_kos }}"
                                            class="w-full h-full object-cover rounded-[20px]" />
                                    @endif
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-100 to-blue-100 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                flex items-center justify-center rounded-[20px]">
                                        <i class="bi bi-house text-[#5C00CC] text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <h3 class="text-black text-sm font-semibold mb-1">{{ $kos->nama_kos }}</h3>
                                <span class="text-gray-500">★ {{ number_format($kos->mean_rating, 1) }}</span>
                            </div>

                            @php
                                $hargaTerendah = null;
                                foreach ($kos->kamar as $kamar) {
                                    if ($kamar->status != 'terisi' && $kamar->hargaSewa->count() > 0) {
                                        $harga = $kamar->hargaSewa->first()->harga;
                                        if ($hargaTerendah === null || $harga < $hargaTerendah) {
                                            $hargaTerendah = $harga;
                                        }
                                    }
                                }
                            @endphp
                            <p class="text-xs text-gray-600 mb-1">
                                @if($hargaTerendah)
                                    Rp. {{ number_format($hargaTerendah, 0, ',', '.') }}/bulan
                                @else
                                    Harga mulai dari...
                                @endif
                            </p>
                            <div class="flex justify-between items-center text-[11px] text-gray-500">
                                @if($kos->kamar_tersedia > 3)
                                    <span></span>
                                @elseif($kos->kamar_tersedia <= 3)
                                    <span>Sisa {{ $kos->kamar_tersedia }} kamar</span>
                                @else
                                    <span class="text-red-500 font-medium">Penuh</span>
                                @endif
                            </div>
                        </article>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-8 text-gray-500">
                        <p>Belum ada data kos</p>
                    </div>
                @endforelse
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
    <script>
        // ==================== VARIABEL GLOBAL ====================
        let chatVisible = false;
        let currentRoomId = null;
        let authUser = null;
        let pollingInterval = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // ==================== FUNGSI INISIALISASI ====================
        function initAuthUser() {
            return window.authUser && window.authUser.id;
        }

        // ==================== FUNGSI UTAMA ====================
        function showChat() {
            // 1. CEK LOGIN
            if (!initAuthUser()) {
                alert('Silakan login terlebih dahulu untuk mengakses chat');
                return;
            }

            authUser = window.authUser;

            // 2. BUKA OVERLAY & LOAD CHAT ROOMS
            openChatOverlay();
            loadChatRooms();
        }

        // ==================== FUNGSI LOAD DAFTAR CHAT ROOMS ====================
        async function loadChatRooms() {
            try {
                const response = await fetch('/chat/rooms', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) throw new Error('Failed to load chat rooms');

                const data = await response.json();
                renderChatRooms(data.rooms);

            } catch (error) {
                console.error('Error loading chat rooms:', error);
                showErrorInChatList('Gagal memuat percakapan');
            }
        }

        // ==================== FUNGSI RENDER DAFTAR CHAT ROOMS ====================
        function renderChatRooms(rooms) {
            const chatList = document.getElementById('chat-list');
            if (!chatList) return;

            if (!rooms || rooms.length === 0) {
                chatList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-comments text-3xl mb-3 text-gray-300"></i>
                        <p class="font-medium">Belum ada percakapan</p>
                        <p class="text-sm mt-1">Mulai chat dari halaman detail kos</p>
                    </div>
                `;
                return;
            }

            let html = '';

            rooms.forEach(room => {
                // Tentukan nama kontak berdasarkan role user
                let contactName = '';
                let lastMessage = 'Belum ada pesan';
                let lastTime = '';
                let avatarChar = 'K';

                if (authUser.role === 'penyewa') {
                    // Penyewa: lihat nama kos/pemilik
                    if (room.kos) {
                        contactName = room.kos.nama_kos || 'Kos';
                        avatarChar = contactName.charAt(0).toUpperCase();
                    }
                } else {
                    // Pemilik: lihat nama penyewa
                    if (room.user) {
                        contactName = room.user.name || 'Penyewa';
                        avatarChar = contactName.charAt(0).toUpperCase();
                    }
                }

                // Ambil pesan terakhir
                if (room.messages && room.messages.length > 0) {
                    const lastMsg = room.messages[0];
                    lastMessage = lastMsg.pesan || '';

                    if (lastMessage.length > 30) {
                        lastMessage = lastMessage.substring(0, 30) + '...';
                    }

                    // Format waktu
                    if (lastMsg.created_at) {
                        lastTime = formatTimeFromDB(lastMsg.created_at);
                    }
                }

                html += `
                    <button class="chat-contact flex items-center w-full p-3 hover:bg-gray-50 rounded-lg transition"
                            onclick="openChatRoom(${room.id})">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 
                                    flex items-center justify-center text-white font-semibold flex-shrink-0">
                            ${avatarChar}
                        </div>
                        <div class="ml-3 text-left flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <p class="text-black font-semibold truncate">${contactName}</p>
                                <span class="text-xs text-gray-400 flex-shrink-0 ml-2">${lastTime}</span>
                            </div>
                            <p class="text-sm text-gray-500 truncate mt-1">${lastMessage}</p>
                        </div>
                    </button>
                    <hr class="border-gray-100">
                `;
            });

            chatList.innerHTML = html;
        }

        // ==================== FUNGSI BUKA CHAT ROOM ====================
        async function openChatRoom(roomId) {
            try {
                currentRoomId = roomId;

                const response = await fetch(`/chat/rooms/${roomId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });
                if (!response.ok) throw new Error('Failed to load chat');
                const data = await response.json();

                const msgResponse = await fetch(`/chat/rooms/${roomId}/messages`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });
                if (!msgResponse.ok) throw new Error('Failed to load messages');
                const msgData = await msgResponse.json();

                if (data.success && msgData.success) {
                    // Update header dengan nama pemilik
                    updateDetailHeader(data.room);
                    // Tampilkan messages
                    showChatDetail(roomId, msgData.messages);
                }
                // Tampilkan detail chat
                // showChatDetail(roomId, data.messages);

            } catch (error) {
                console.error('Error opening chat room:', error);
                alert('Gagal membuka percakapan');
            }
        }

        function updateDetailHeader(room) {
            // Tentukan nama kontak berdasarkan role user
            let contactName = '';
            let contactRole = '';
            let avatarChar = '';

            if (authUser.role === 'penyewa') {
                // Penyewa chat dengan pemilik kos
                if (room.kos && room.kos.owner) {
                    contactName = room.kos.owner.name || 'Pemilik Kos';
                    contactRole = 'Pemilik Kos';
                } else if (room.kos) {
                    contactName = room.kos.nama_kos || 'Kos';
                    contactRole = 'Pemilik Kos';
                }
            } else {
                // Pemilik chat dengan penyewa
                if (room.user) {
                    contactName = room.user.name || 'Penyewa';
                    contactRole = 'Penyewa';
                }
            }

            // Ambil huruf pertama untuk avatar
            if (contactName) {
                avatarChar = contactName.charAt(0).toUpperCase();
            }

            // Update UI elements
            const nameEl = document.getElementById('chat-contact-name');
            const roleEl = document.getElementById('chat-contact-role');
            const avatarEl = document.getElementById('chat-avatar');

            if (nameEl) nameEl.textContent = contactName;
            if (roleEl) roleEl.textContent = contactRole;
            if (avatarEl) {
                avatarEl.textContent = avatarChar;
                // Jika avatar kosong, sembunyikan
                avatarEl.classList.add('flex', 'items-center', 'justify-center', 'text-white', 'font-semibold');
            }

            console.log('Header updated:', { contactName, contactRole, avatarChar });
        }

        // ==================== FUNGSI TAMPILKAN DETAIL CHAT ====================
        async function showChatDetail(roomId, messages = null) {
            // 1. Switch view ke detail
            document.getElementById('chat-list').classList.add('hidden');
            document.getElementById('chat-detail').classList.remove('hidden');
            document.getElementById('back-to-list').classList.remove('hidden');

            // 2. Jika belum ada messages, fetch dari server
            if (!messages) {
                try {
                    const response = await fetch(`/chat/rooms/${roomId}/messages`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();
                    if (data.success) messages = data.messages;
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            // 3. Render messages
            if (messages) {
                renderMessagesInDetail(messages);
            }

            // 4. Mulai polling untuk real-time
            startMessagePolling(roomId);
        }

        // ==================== FUNGSI RENDER MESSAGES DI DETAIL ====================
        function renderMessagesInDetail(messages) {
            const messagesContainer = document.querySelector('#chat-detail .space-y-4');
            if (!messagesContainer) return;

            if (!messages || messages.length === 0) {
                messagesContainer.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <p>Belum ada pesan</p>
                        <p class="text-sm mt-1">Mulai percakapan sekarang!</p>
                    </div>
                `;
                return;
            }

            let html = '';
            messages.forEach(msg => {
                const isCurrentUser = msg.sender_id == authUser.id;
                const timeStr = formatTimeFromDB(msg.created_at);

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

            // Scroll ke bawah
            setTimeout(() => {
                const container = document.querySelector('#chat-detail .overflow-y-auto');
                if (container) container.scrollTop = container.scrollHeight;
            }, 100);
        }

        // ==================== FUNGSI KIRIM PESAN ====================
        async function sendMessage() {
            if (!currentRoomId) return;

            const input = document.querySelector('#chat-detail input[type="text"]');
            const message = input.value.trim();

            if (message === '') return;

            const tempMsgId = 'temp-' + Date.now();

            try {
                // Tampilkan pesan loading
                const messagesContainer = document.querySelector('#chat-detail .space-y-4');
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

                    const container = document.querySelector('#chat-detail .overflow-y-auto');
                    if (container) container.scrollTop = container.scrollHeight;
                }

                // Kirim ke server
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

                // Hapus loading
                const tempElement = document.getElementById(tempMsgId);
                if (tempElement) tempElement.remove();

                if (data.success) {
                    input.value = '';
                    // Refresh messages
                    await showChatDetail(currentRoomId);
                } else {
                    alert('Gagal mengirim pesan: ' + (data.error || 'Unknown error'));
                }

            } catch (error) {
                console.error('Error sending message:', error);
                alert('Terjadi kesalahan saat mengirim pesan');

                const tempElement = document.getElementById(tempMsgId);
                if (tempElement) tempElement.remove();
            }
        }

        // ==================== FUNGSI HELPER ====================
        function formatTimeFromDB(dateString) {
            if (!dateString) return 'Baru saja';

            try {
                // Parse sebagai UTC dan konversi ke lokal
                const isoDate = dateString.includes(' ')
                    ? dateString.replace(' ', 'T') + 'Z'
                    : dateString;

                const date = new Date(isoDate);

                if (isNaN(date.getTime())) {
                    if (dateString.includes(' ')) {
                        const timePart = dateString.split(' ')[1];
                        const [hour, minute] = timePart.split(':');
                        return `${hour}:${minute}`;
                    }
                    return 'Baru saja';
                }

                return date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

            } catch (e) {
                return 'Baru saja';
            }
        }

        function startMessagePolling(roomId) {
            if (pollingInterval) clearInterval(pollingInterval);

            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch(`/chat/rooms/${roomId}/messages`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();
                    if (data.success && currentRoomId === roomId) {
                        renderMessagesInDetail(data.messages);
                    }
                } catch (error) {
                    console.log('Polling error:', error);
                }
            }, 3000);
        }

        function showErrorInChatList(message) {
            const chatList = document.getElementById('chat-list');
            if (chatList) {
                chatList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-3 text-red-300"></i>
                        <p>${message}</p>
                        <button onclick="loadChatRooms()" class="mt-3 px-4 py-2 bg-[#5C00CC] text-white 
                            rounded-lg text-sm hover:bg-purple-700 transition">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        function openChatOverlay() {
            const overlay = document.getElementById('chat-overlay');
            const panel = overlay.querySelector('.absolute.inset-y-0');

            overlay.classList.remove('hidden');

            setTimeout(() => {
                panel.style.transform = 'translateX(0)';
            }, 10);

            chatVisible = true;
            document.body.style.overflow = 'hidden';
        }

        function showChatList() {
            document.getElementById('chat-list').classList.remove('hidden');
            document.getElementById('chat-detail').classList.add('hidden');
            document.getElementById('back-to-list').classList.add('hidden');
            currentRoomId = null;

            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }

            // Reload chat rooms
            loadChatRooms();
        }

        function hideChat() {
            const overlay = document.getElementById('chat-overlay');
            const panel = overlay.querySelector('.absolute.inset-y-0');

            panel.style.transform = 'translateX(100%)';

            setTimeout(() => {
                overlay.classList.add('hidden');
                chatVisible = false;
                document.body.style.overflow = '';

                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }, 300);
        }

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', () => {
            // User data dari Blade
            @auth
                window.authUser = {
                    id: {{ auth()->id() }},
                        name: '{{ auth()->user()->name }}',
                            role: '{{ auth()->user()->role }}',
                                email: '{{ auth()->user()->email }}'
                };
            @endauth

            // Enter to send
            const input = document.querySelector('#chat-detail input[type="text"]');
            if (input) {
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        sendMessage();
                    }
                });
            }

            // Tombol send
            const sendBtn = document.querySelector('#chat-detail button');
            if (sendBtn && !sendBtn.onclick) {
                sendBtn.addEventListener('click', sendMessage);
            }
        });

        // ESC to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && chatVisible) hideChat();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== MODAL FUNCTIONALITY ==========
            const modal = document.getElementById('login-modal');

            // Function buka modal
            function openModal(role = 'penyewa') {
                modal.classList.add('open');
                document.body.style.overflow = 'hidden';

                // Set role di input hidden jika ada
                const loginRoleInput = document.getElementById('login_role');
                if (loginRoleInput) {
                    loginRoleInput.value = role;
                }

                const registerRoleInput = document.getElementById('register_role');
                if (registerRoleInput) {
                    registerRoleInput.value = role;
                }

                // if (role === 'pemilik' || role === 'penyewa') {
                //     // Default show tab login
                //     switchTab('login');
                // }

                document.querySelectorAll('input[name="role"]').forEach(input => {
                    input.value = role;
                });
            }

            // Function tutup modal
            function closeModal() {
                modal.classList.remove('open');
                document.body.style.overflow = '';
            }

            // ========== EVENT LISTENERS UNTUK SEMUA BUTTON LOGIN ==========
            // Handle button pemilik di header (yang ada ID)
            const pemilikBtn = document.getElementById('open-modal-btn');
            if (pemilikBtn) {
                pemilikBtn.addEventListener('click', function () {
                    openModal('pemilik');
                });
            }

            // Handle button penyewa di dropdown (pake class atau data attribute)
            document.querySelectorAll('[data-open-modal="login"], .open-modal-penyewa').forEach(btn => {
                btn.addEventListener('click', function () {
                    const role = this.dataset.role || 'penyewa';
                    openModal(role);
                });
            });

            // Juga handle jika ada button dengan class tertentu
            document.querySelectorAll('.open-modal-penyewa').forEach(btn => {
                btn.addEventListener('click', function () {
                    openModal('penyewa');
                });
            });

            // ========== CLOSE MODAL FUNCTIONALITY ==========
            // Tutup modal kalau klik di luar konten modal
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            // Tutup modal dengan ESC key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal.classList.contains('open')) {
                    closeModal();
                }
            });

            // ========== TAB SWITCHING FUNCTIONALITY ==========
            const tabBtns = document.querySelectorAll('.tab-btn');
            const switchTabBtns = document.querySelectorAll('.switch-tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            function switchTab(tabName) {
                // Remove active class from all tabs
                tabBtns.forEach(btn => {
                    btn.classList.remove('show');
                    btn.classList.remove('border-[#5C00CC]');
                    btn.classList.add('text-gray-500');
                });

                tabPanes.forEach(pane => pane.classList.remove('show'));

                // Add active class to selected tab
                const activeTabBtn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
                const activePane = document.getElementById(`tab-${tabName}`);

                if (activeTabBtn) {
                    activeTabBtn.classList.add('show', 'border-[#5C00CC]', 'text-[#5C00CC]');
                    activeTabBtn.classList.remove('text-gray-500');
                }

                if (activePane) {
                    activePane.classList.add('show');
                }
            }

            // Event listeners for tab buttons
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const tabName = this.getAttribute('data-tab');
                    switchTab(tabName);
                });
            });

            // Event listeners for switch buttons (link "Daftar" di login, "Login" di daftar)
            switchTabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const tabName = this.getAttribute('data-tab');
                    switchTab(tabName);
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resetForm = document.getElementById('resetPasswordForm');
            const resetSubmitBtn = document.getElementById('resetSubmitBtn');
            const resetSpinner = document.getElementById('resetSpinner');
            const resetBtnText = document.getElementById('resetBtnText');
            const resetMessages = document.getElementById('resetMessages');
            const resetResult = document.getElementById('resetResult');
            const resetLinkDisplay = document.getElementById('resetLinkDisplay');
            const copyResetLinkBtn = document.getElementById('copyResetLinkBtn');
            const copyIcon = document.getElementById('copyIcon');
            const openResetLinkBtn = document.getElementById('openResetLinkBtn');
            const expiryTime = document.getElementById('expiryTime');

            resetForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Reset UI
                resetMessages.innerHTML = '';
                resetResult.classList.add('hidden');

                // Show loading
                resetSubmitBtn.disabled = true;
                resetSpinner.classList.remove('hidden');
                resetBtnText.textContent = 'Memproses...';

                try {
                    const formData = {
                        email: document.getElementById('reset-email').value,
                        _token: '{{ csrf_token() }}'
                    };

                    const response = await fetch('{{ route("password.generate-link") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success result
                        resetLinkDisplay.value = data.link;
                        if (data.expires_at) {
                            expiryTime.textContent = data.expires_at + ' ' + (data.timezone || '');
                        }
                        resetResult.classList.remove('hidden');

                        // Scroll to result
                        resetResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        // Show error
                        resetMessages.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 
                               px-4 py-3 rounded mb-4 text-sm">
                        ${data.message || 'Terjadi kesalahan. Coba lagi.'}
                    </div>
                `;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    resetMessages.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 
                           px-4 py-3 rounded mb-4 text-sm">
                    Gagal terhubung ke server. Periksa koneksi internet.
                </div>
            `;
                } finally {
                    // Reset button state
                    resetSubmitBtn.disabled = false;
                    resetSpinner.classList.add('hidden');
                    resetBtnText.textContent = 'Kirim Link Reset';
                }
            });

            // Copy Link Functionality
            copyResetLinkBtn.addEventListener('click', function () {
                resetLinkDisplay.select();
                resetLinkDisplay.setSelectionRange(0, 99999);

                navigator.clipboard.writeText(resetLinkDisplay.value).then(() => {
                    // Change icon to checkmark
                    copyIcon.classList.remove('icon-[tabler--clipboard]');
                    copyIcon.classList.add('icon-[tabler--clipboard-check]', 'text-green-600');

                    // Revert after 2 seconds
                    setTimeout(() => {
                        copyIcon.classList.remove('icon-[tabler--clipboard-check]', 'text-green-600');
                        copyIcon.classList.add('icon-[tabler--clipboard]');
                    }, 2000);
                });
            });

            // Open Link in New Tab
            openResetLinkBtn.addEventListener('click', function () {
                window.open(resetLinkDisplay.value, '_blank');
            });
        });
    </script>
</body>

</html>