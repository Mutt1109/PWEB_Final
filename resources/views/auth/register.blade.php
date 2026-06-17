<!DOCTYPE html>
<html lang="id">
{{-- Halaman Register — Sistem Pemesanan Tiket --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — IKL Ticket Hub</title>
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
            <p class="text-gray-500 text-sm mt-2">Buat akun baru untuk memesan tiket</p>
        </div>

        {{-- Form Register --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-xl font-bold text-gray-800 mb-6">Daftar Akun Baru</h1>

            {{-- Tampilkan error dari server --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form id="register-form" method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                {{-- Nama --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 focus:border-ikl-500 transition"
                           placeholder="Nama lengkap">
                    <p id="name-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

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
                           placeholder="Minimal 8 karakter">
                    <p id="password-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 focus:border-ikl-500 transition"
                           placeholder="Ketik ulang password">
                    <p id="confirm-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                {{-- Tombol Register --}}
                <button type="submit"
                        class="w-full bg-ikl-600 text-white font-semibold py-2.5 rounded-lg hover:bg-ikl-700 transition text-sm">
                    Daftar
                </button>
            </form>

            {{-- Link Login --}}
            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-ikl-600 font-medium hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>

    {{-- JavaScript: Validasi Form Client-Side (DOM Manipulation) --}}
    <script>
        // Validasi form register sebelum dikirim ke server
        document.getElementById('register-form').addEventListener('submit', function(e) {
            var name = document.getElementById('name');
            var email = document.getElementById('email');
            var password = document.getElementById('password');
            var confirm = document.getElementById('password_confirmation');
            var valid = true;

            // Helper: tampilkan error
            function showError(input, errorEl, message) {
                document.getElementById(errorEl).textContent = message;
                document.getElementById(errorEl).classList.remove('hidden');
                input.classList.add('border-red-500');
            }

            // Reset semua error
            ['name-error', 'email-error', 'password-error', 'confirm-error'].forEach(function(id) {
                document.getElementById(id).classList.add('hidden');
            });
            [name, email, password, confirm].forEach(function(el) {
                el.classList.remove('border-red-500');
            });

            // Validasi nama
            if (!name.value.trim()) {
                showError(name, 'name-error', 'Nama wajib diisi.');
                valid = false;
            }

            // Validasi email
            if (!email.value.trim()) {
                showError(email, 'email-error', 'Email wajib diisi.');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                showError(email, 'email-error', 'Format email tidak valid.');
                valid = false;
            }

            // Validasi password
            if (!password.value) {
                showError(password, 'password-error', 'Password wajib diisi.');
                valid = false;
            } else if (password.value.length < 8) {
                showError(password, 'password-error', 'Password minimal 8 karakter.');
                valid = false;
            }

            // Validasi konfirmasi password
            if (password.value !== confirm.value) {
                showError(confirm, 'confirm-error', 'Konfirmasi password tidak cocok.');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>

