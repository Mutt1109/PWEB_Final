<!DOCTYPE html>
<html lang="id">
{{-- Layout utama aplikasi Sistem Pemesanan Tiket --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pemesanan Tiket')</title>
    <meta name="description" content="Sistem pemesanan tiket pertandingan esports online.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- ===== NAVBAR ===== --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">

                {{-- Logo / Brand --}}
                <a href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('matches.index') : route('dashboard') }}" class="flex items-center gap-2 text-lg font-bold text-ikl-600 no-underline">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span>IKL Ticket Hub</span>
                </a>

                {{-- Menu Desktop --}}
                <div class="hidden md:flex items-center gap-1">
                    @auth
                        @if(auth()->user()->role !== 'admin')
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-ikl-50 text-ikl-700' : 'text-gray-600 hover:bg-gray-100' }} transition">
                            Dashboard
                        </a>
                        @endif

                        {{-- Menu --}}
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('matches.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('matches.*') ? 'bg-ikl-50 text-ikl-700' : 'text-gray-600 hover:bg-gray-100' }} transition">
                            Kelola Pertandingan
                        </a>
                        <a href="{{ route('admin.tickets.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.tickets.*') ? 'bg-ikl-50 text-ikl-700' : 'text-gray-600 hover:bg-gray-100' }} transition">
                            Kelola Pesanan
                        </a>
                        @endif
                    @endauth
                </div>

                {{-- User Info & Logout --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-ikl-600 rounded-lg hover:bg-ikl-700 transition">
                            Login
                        </a>
                    @endauth
                </div>

                {{-- Tombol Mobile Menu --}}
                <button id="mobile-toggle" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu (hidden by default) --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white px-4 py-3 space-y-1">
            @auth
                @if(auth()->user()->role !== 'admin')
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Dashboard</a>
                @endif
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('matches.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Kelola Pertandingan</a>
                <a href="{{ route('admin.tickets.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Kelola Pesanan</a>
                @endif
                <div class="border-t border-gray-100 pt-2 mt-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:underline">Logout</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-ikl-600 hover:bg-ikl-50">Login</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Register</a>
            @endauth
        </div>
    </nav>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
        <div id="flash-msg" class="max-w-6xl mx-auto mt-4 px-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div id="flash-msg" class="max-w-6xl mx-auto mt-4 px-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- ===== KONTEN HALAMAN ===== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-white border-t border-gray-200 py-6 mt-8">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} IKL Ticket Hub — Sistem Pemesanan Tiket Pertandingan
        </div>
    </footer>

    {{-- ===== SCRIPTS ===== --}}
    <script>
        // Toggle mobile menu
        document.getElementById('mobile-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Auto-hide flash message setelah 4 detik
        var flash = document.getElementById('flash-msg');
        if (flash) {
            setTimeout(function() {
                flash.style.transition = 'opacity 0.3s';
                flash.style.opacity = '0';
                setTimeout(function() { flash.remove(); }, 300);
            }, 4000);
        }
    </script>

    {{-- Stack untuk script tambahan dari halaman lain --}}
    @stack('scripts')

    {{-- SweetAlert2 untuk konfirmasi hapus --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.form-delete').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C0281A',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
</body>
</html>

