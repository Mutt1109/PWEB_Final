<!DOCTYPE html>
<html lang="id">
{{-- Halaman Login — Sistem Pemesanan Tiket --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — IKL Ticket Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 text-2xl font-bold text-ikl-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                IKL Ticket Hub
            </div>
            <p class="text-gray-500 text-sm mt-2">Sistem Pemesanan Tiket Pertandingan</p>
        </div>

        {{-- Form Login --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-xl font-bold text-gray-800 mb-6">Masuk ke Akun</h1>

            {{-- Tampilkan error dari server --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 focus:border-ikl-500 transition"
                           placeholder="contoh@email.com">
                    <p id="email-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 focus:border-ikl-500 transition"
                           placeholder="Masukkan password">
                    <p id="password-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center mb-6">
                    <input id="remember" type="checkbox" name="remember"
                           class="w-4 h-4 text-ikl-600 border-gray-300 rounded focus:ring-ikl-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                {{-- Tombol Login --}}
                <button type="submit" id="btn-login"
                        class="w-full bg-ikl-600 text-white font-semibold py-2.5 rounded-lg hover:bg-ikl-700 transition text-sm">
                    Masuk
                </button>
            </form>

            {{-- Link Register --}}
            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-ikl-600 font-medium hover:underline">Daftar di sini</a>
            </p>
        </div>
    </div>

    {{-- JavaScript: Validasi Form Client-Side (DOM Manipulation) --}}
    <script>
        // Validasi form login sebelum dikirim ke server
        document.getElementById('login-form').addEventListener('submit', function(e) {
            var email = document.getElementById('email');
            var password = document.getElementById('password');
            var emailError = document.getElementById('email-error');
            var passwordError = document.getElementById('password-error');
            var valid = true;

            // Reset error
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            email.classList.remove('border-red-500');
            password.classList.remove('border-red-500');

            // Validasi email
            if (!email.value.trim()) {
                emailError.textContent = 'Email wajib diisi.';
                emailError.classList.remove('hidden');
                email.classList.add('border-red-500');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                emailError.textContent = 'Format email tidak valid.';
                emailError.classList.remove('hidden');
                email.classList.add('border-red-500');
                valid = false;
            }

            // Validasi password
            if (!password.value) {
                passwordError.textContent = 'Password wajib diisi.';
                passwordError.classList.remove('hidden');
                password.classList.add('border-red-500');
                valid = false;
            }

            // Cegah submit jika tidak valid
            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>

