<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body class="bg-[#f5f5f5] text-black">
    <main class="px-2 md:px-[61px] min-h-[calc(100vh)] flex items-center justify-center">
        <div class="bg-white rounded-2xl p-4 md:px-6 md:py-7 md: h-130 w-100 border border-gray-200 shadow-md">
            <p class="title text-2xl font-semibold">Reset Password</p>
            <p class="mt-3 text-gray-500">Masukkan email dan password baru Anda</p>

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.reset.submit') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group mt-8">
                    <label class="label-text text-black" for="email">Email</label>
                    <div class="input text-black bg-white border-gray-400">
                        <input type="email" name="email" placeholder="Email" 
                            id="email" value="{{ old('email', $email ?? '') }}" required
                            readonly />
                    </div>
                </div>

                <div class="input-group mt-6">
                    <label class="label-text text-black" for="password">Password Baru</label>
                    <div class="input mb-6 text-black bg-white border-gray-400">
                        <input id="password" type="password" name="password"
                            placeholder="Min 8 karakter" required />
                        <button type="button" data-toggle-password='{ "target": "#password" }'
                            class="block cursor-pointer" aria-label="password toggle">
                            <span
                                class="icon-[tabler--eye] text-gray-400 password-active:block hidden size-5"></span>
                            <span
                                class="icon-[tabler--eye-off] text-gray-400 password-active:hidden block size-5"></span>
                        </button>
                    </div>

                    <label class="label-text text-black" for="password_confirmation">Konfirmasi
                        Password Baru</label>
                    <div class="input mb-6 text-black bg-white border-gray-400">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="Ulangi password" required />
                        <button type="button"
                            data-toggle-password='{ "target": "#password_confirmation" }'
                            class="block cursor-pointer" aria-label="password toggle">
                            <span
                                class="icon-[tabler--eye] text-gray-400 password-active:block hidden size-5"></span>
                            <span
                                class="icon-[tabler--eye-off] text-gray-400 password-active:hidden block size-5"></span>
                        </button>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit"
                        class="bg-[#5C00CC] text-white w-50 h-10 text-sm font-medium rounded-lg">
                        Reset Password
                    </button>
                </div>
            </form>
            <div class="flex justify-center mt-4 text-sm text-[#5C00CC]">
                <a href="{{ route('login') }}">← Kembali ke Login</a>
            </div>
        </div>
</main>
    <script src="{{ asset('js/flyonui.js') }}"></script>
</body>

</html>