@extends('layouts.app')
@section('title', 'Dashboard — IKL Ticket Hub')
@section('content')

{{-- ===== HEADER + STATISTIK ===== --}}
<section class="bg-gradient-to-r from-ikl-600 to-ikl-800 py-10 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-white mb-1">
            Selamat Datang, {{ auth()->user()->name }}! 👋
        </h1>
        <p class="text-ikl-200 text-sm">Kelola pemesanan tiket pertandingan kamu di sini.</p>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
                <p class="text-ikl-200 text-xs font-medium uppercase tracking-wide">Total Booking</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $totalBooking }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
                <p class="text-ikl-200 text-xs font-medium uppercase tracking-wide">Booking Aktif</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $bookingAktif }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PENCARIAN PERTANDINGAN (AJAX/Fetch API) ===== --}}
<section class="py-8 px-4 bg-gray-50 border-b border-gray-200">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-lg font-bold text-gray-800 mb-4">🔍 Cari Pertandingan</h2>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            {{-- Form pencarian (dikirim via AJAX, tanpa reload halaman) --}}
            <form id="search-form" onsubmit="return false;" class="flex gap-3">
                <input id="search-input" type="text" placeholder="Ketik nama tim atau venue..."
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition">
                <button type="submit" id="btn-search"
                        class="px-5 py-2.5 bg-ikl-600 text-white text-sm font-medium rounded-lg hover:bg-ikl-700 transition">
                    Cari
                </button>
            </form>

            {{-- Loading indicator --}}
            <div id="search-loading" class="hidden mt-4 text-sm text-gray-500 flex items-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Mencari...
            </div>

            {{-- Hasil pencarian muncul di sini tanpa reload halaman --}}
            <div id="search-results" class="mt-4"></div>
        </div>
    </div>
</section>

{{-- ===== JADWAL PERTANDINGAN AKTIF ===== --}}
<section class="py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-lg font-bold text-gray-800 mb-4">📅 Jadwal Pertandingan Aktif</h2>

        @if($matches->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <p class="text-gray-500 text-sm">Belum ada pertandingan aktif saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($matches as $match)
                <article class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="text-center mb-3">
                        <p class="font-semibold text-gray-800">{{ $match->tim_tamu }}</p>
                        <p class="text-ikl-600 font-bold text-lg my-1">VS</p>
                        <p class="font-semibold text-gray-800">{{ $match->tim_kandang }}</p>
                    </div>
                    <div class="border-t border-gray-100 pt-3 space-y-1 text-sm text-gray-500">
                        <p>📅 {{ $match->tanggal->format('d M Y') }} · {{ $match->jam }} WIB</p>
                        <p>📍 {{ $match->venue }}</p>
                        <p>💰 Rp {{ number_format($match->harga, 0, ',', '.') }} / tiket</p>
                    </div>
                </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ===== FORM BOOKING TIKET (CREATE) ===== --}}
<section id="form-booking" class="py-8 px-4 bg-gray-50 border-t border-gray-200">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-lg font-bold text-gray-800 mb-4">🎫 Booking Tiket Baru</h2>

        <form id="booking-form" action="{{ route('tickets.store') }}" method="POST" novalidate
              class="bg-white rounded-xl border border-gray-200 p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Pertandingan --}}
                <div>
                    <label for="match_id" class="block text-sm font-medium text-gray-700 mb-1">Pertandingan</label>
                    <select id="match_id" name="match_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition">
                        <option value="">-- Pilih Pertandingan --</option>
                        @foreach($matches as $match)
                            <option value="{{ $match->id }}" data-harga="{{ $match->harga }}" {{ old('match_id') == $match->id ? 'selected' : '' }}>
                                {{ $match->tim_tamu }} vs {{ $match->tim_kandang }} · {{ $match->tanggal->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('match_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nama Pemesan --}}
                <div>
                    <label for="nama_pemesan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                    <input id="nama_pemesan" type="text" name="nama_pemesan" value="{{ old('nama_pemesan') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition"
                           placeholder="Nama lengkap">
                    @error('nama_pemesan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="booking-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="booking-email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition"
                           placeholder="email@contoh.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP <span class="text-gray-400">(opsional)</span></label>
                    <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition"
                           placeholder="08xxxxxxxxxx">
                </div>

                {{-- Jumlah --}}
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tiket</label>
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" max="10" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition">
                    @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Estimasi Harga (DOM Manipulation) --}}
                <div class="flex items-end">
                    <div class="w-full bg-ikl-50 rounded-lg px-4 py-2.5 border border-ikl-200">
                        <p class="text-xs text-ikl-600 font-medium">Estimasi Total</p>
                        <p id="estimasi-harga" class="text-xl font-bold text-ikl-700 mt-1">Rp 0</p>
                    </div>
                </div>
            </div>

            {{-- Error validasi client-side --}}
            <div id="form-errors" class="hidden mt-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3"></div>

            {{-- Tombol Submit --}}
            <div class="flex justify-end mt-6">
                <button type="submit"
                        class="px-6 py-2.5 bg-ikl-600 text-white text-sm font-semibold rounded-lg hover:bg-ikl-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Booking Sekarang
                </button>
            </div>
        </form>
    </div>
</section>

{{-- ===== DAFTAR TIKET SAYA (READ) ===== --}}
<section class="py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-lg font-bold text-gray-800 mb-4">📋 Tiket Saya ({{ $tickets->count() }})</h2>

        @if($tickets->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <p class="text-gray-500 text-sm">Belum ada tiket. Pesan tiket pertamamu dari form di atas!</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                {{-- Tabel responsif --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Kode</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Pertandingan</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Pemesan</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600">Jumlah</th>
                                <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Kode Booking --}}
                                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $ticket->kode_booking }}</td>

                                {{-- Pertandingan --}}
                                <td class="px-4 py-3">
                                    @if($ticket->match)
                                        <p class="font-medium text-gray-800">{{ $ticket->match->tim_tamu }} vs {{ $ticket->match->tim_kandang }}</p>
                                        <p class="text-xs text-gray-400">{{ $ticket->match->tanggal->format('d M Y') }}</p>
                                    @else
                                        <span class="text-gray-400 italic">Data tidak ada</span>
                                    @endif
                                </td>

                                {{-- Pemesan --}}
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-700">{{ $ticket->nama_pemesan }}</p>
                                    <p class="text-xs text-gray-400">{{ $ticket->email }}</p>
                                </td>

                                {{-- Jumlah --}}
                                <td class="px-4 py-3 text-center font-semibold">{{ $ticket->jumlah }}</td>

                                {{-- Total --}}
                                <td class="px-4 py-3 text-right font-medium text-gray-800">
                                    Rp {{ number_format($ticket->total_harga, 0, ',', '.') }}
                                </td>

                                {{-- Status (VIEW ONLY) --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full 
                                        {{ $ticket->status === 'Aktif' ? 'bg-green-100 text-green-700' : ($ticket->status === 'Terpakai' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>

                                {{-- Aksi (BATALKAN BOOKING) --}}
                                <td class="px-4 py-3 text-center">
                                    @if($ticket->status === 'Aktif')
                                    <form action="{{ route('tickets.cancel', $ticket) }}" method="POST" class="form-delete inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition" title="Batalkan Booking ini">
                                            Batalkan
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-gray-400 text-xs italic">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
// =====================================================================
// JavaScript: Estimasi Harga (DOM Manipulation)
// Menghitung estimasi total harga secara real-time saat user mengubah
// pertandingan atau jumlah tiket, tanpa reload halaman.
// =====================================================================
(function() {
    var matchSelect = document.getElementById('match_id');
    var jumlahInput = document.getElementById('jumlah');
    var estimasiEl  = document.getElementById('estimasi-harga');

    // Fungsi untuk menghitung dan menampilkan estimasi harga
    function hitungEstimasi() {
        var selected = matchSelect.options[matchSelect.selectedIndex];
        var harga = selected ? parseFloat(selected.dataset.harga) || 0 : 0;
        var jumlah = parseInt(jumlahInput.value) || 0;
        var total = harga * jumlah;

        // Update tampilan estimasi (DOM Manipulation)
        estimasiEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Event listener: hitung ulang saat pertandingan atau jumlah berubah
    matchSelect.addEventListener('change', hitungEstimasi);
    jumlahInput.addEventListener('input', hitungEstimasi);

    // Hitung saat halaman pertama kali dimuat
    hitungEstimasi();
})();

// =====================================================================
// JavaScript: Validasi Form Booking Client-Side
// Memvalidasi input sebelum dikirim ke server (DOM Manipulation).
// =====================================================================
(function() {
    var form = document.getElementById('booking-form');
    var errorsDiv = document.getElementById('form-errors');

    form.addEventListener('submit', function(e) {
        var errors = [];

        // Validasi pertandingan
        if (!document.getElementById('match_id').value) {
            errors.push('Pilih pertandingan terlebih dahulu.');
        }

        // Validasi nama
        if (!document.getElementById('nama_pemesan').value.trim()) {
            errors.push('Nama pemesan wajib diisi.');
        }

        // Validasi email
        var email = document.getElementById('booking-email').value;
        if (!email.trim()) {
            errors.push('Email wajib diisi.');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push('Format email tidak valid.');
        }

        // Validasi jumlah
        var jumlah = parseInt(document.getElementById('jumlah').value);
        if (!jumlah || jumlah < 1 || jumlah > 10) {
            errors.push('Jumlah tiket harus antara 1-10.');
        }

        // Tampilkan error jika ada
        if (errors.length > 0) {
            e.preventDefault();
            errorsDiv.innerHTML = errors.map(function(err) { return '<p>• ' + err + '</p>'; }).join('');
            errorsDiv.classList.remove('hidden');
            // Scroll ke form agar user melihat error
            errorsDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            errorsDiv.classList.add('hidden');
        }
    });
})();

// =====================================================================
// AJAX/Fetch API: Pencarian Pertandingan Asinkron
// Mengirim request POST ke server dan menampilkan hasil JSON
// tanpa reload halaman penuh (Komunikasi Asinkron).
// =====================================================================
(function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var input     = document.getElementById('search-input');
    var loading   = document.getElementById('search-loading');
    var results   = document.getElementById('search-results');
    var debounce;

    // Fungsi pencarian via Fetch API
    async function doSearch(query) {
        if (!query || query.length < 2) {
            results.innerHTML = '';
            return;
        }

        // Tampilkan loading indicator
        loading.classList.remove('hidden');
        results.innerHTML = '';

        try {
            // Kirim request POST ke server (AJAX menggunakan Fetch API)
            var response = await fetch('{{ route("api.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ q: query }),
            });

            // Parse response JSON dari server
            var data = await response.json();

            // Sembunyikan loading
            loading.classList.add('hidden');

            // Tampilkan hasil pencarian (DOM Manipulation)
            if (!data.data || data.data.length === 0) {
                results.innerHTML = '<p class="text-gray-500 text-sm italic">Tidak ditemukan hasil untuk "' + query + '".</p>';
                return;
            }

            // Render hasil pencarian ke halaman
            var html = '<p class="text-xs text-gray-500 mb-3">Ditemukan ' + data.count + ' pertandingan</p>';
            data.data.forEach(function(m) {
                var tgl = new Date(m.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                html += '<div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-2 flex flex-wrap items-center justify-between gap-2">'
                      + '<div>'
                      + '<p class="font-semibold text-gray-800 text-sm">' + m.tim_tamu + ' <span class="text-ikl-600">vs</span> ' + m.tim_kandang + '</p>'
                      + '<p class="text-xs text-gray-500 mt-0.5">' + tgl + ' · ' + m.jam + ' WIB · ' + m.venue + '</p>'
                      + '</div>'
                      + '<p class="text-sm font-semibold text-ikl-600">Rp ' + Number(m.harga).toLocaleString('id-ID') + '</p>'
                      + '</div>';
            });

            results.innerHTML = html;

        } catch (error) {
            loading.classList.add('hidden');
            results.innerHTML = '<p class="text-red-500 text-sm italic">Gagal menghubungi server.</p>';
        }
    }

    // Live search: cari otomatis saat user mengetik (debounced 400ms)
    input.addEventListener('input', function() {
        clearTimeout(debounce);
        debounce = setTimeout(function() { doSearch(input.value.trim()); }, 400);
    });

    // Cari saat tombol ditekan
    document.getElementById('search-form').addEventListener('submit', function() {
        doSearch(input.value.trim());
    });
})();
</script>
@endpush

