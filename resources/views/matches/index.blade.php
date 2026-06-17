@extends('layouts.app')
@section('title', 'Kelola Pertandingan — IKL Ticket Hub')
@section('content')

@php
    $dbTeamsTamu = \App\Models\IklMatch::pluck('tim_tamu')->toArray();
    $dbTeamsKandang = \App\Models\IklMatch::pluck('tim_kandang')->toArray();
    $dbVenues = \App\Models\IklMatch::pluck('venue')->toArray();

    $defaultTeams = ['RRQ', 'EVOS Legends', 'ONIC Esports', 'Bigetron', 'Aura Fire', 'Geek Fam', 'Alter Ego', 'Rebellion Zion', 'Dewa United Esports', 'Kagendra', 'Dominator', 'Talon ID', 'Mahadewa'];
    $defaultVenues = ['GBK Arena Jakarta', 'Istora Senayan', 'Tennis Indoor Senayan', 'Jiexpo Kemayoran', 'ICE BSD Tangerang'];

    $teams = array_unique(array_merge($defaultTeams, $dbTeamsTamu, $dbTeamsKandang));
    $venues = array_unique(array_merge($defaultVenues, $dbVenues));
    
    sort($teams);
    sort($venues);
@endphp

{{-- ===== HEADER ===== --}}
<section class="bg-gradient-to-r from-ikl-600 to-ikl-800 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-white">Kelola Pertandingan</h1>
        <p class="text-ikl-200 text-sm mt-1">Tambah, edit, dan hapus data pertandingan</p>
    </div>
</section>

{{-- ===== FORM TAMBAH / EDIT PERTANDINGAN ===== --}}
<section class="py-8 px-4 bg-gray-50 border-b border-gray-200">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            {{ isset($match) ? '✏️ Edit Pertandingan' : '➕ Tambah Pertandingan Baru' }}
        </h2>

        <form id="match-form" method="POST"
              action="{{ isset($match) ? route('matches.update', $match) : route('matches.store') }}"
              novalidate
              class="bg-white rounded-xl border border-gray-200 p-6">
            @csrf
            @if(isset($match)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Tim Tamu --}}
                <div>
                    <label for="tim_tamu" class="block text-sm font-medium text-gray-700 mb-1">Tim Tamu</label>
                    <select id="tim_tamu" name="tim_tamu" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition cursor-pointer">
                        <option value="">-- Pilih Tim Tamu --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team }}" {{ old('tim_tamu', $match->tim_tamu ?? '') == $team ? 'selected' : '' }}>{{ $team }}</option>
                        @endforeach
                    </select>
                    @error('tim_tamu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tim Kandang --}}
                <div>
                    <label for="tim_kandang" class="block text-sm font-medium text-gray-700 mb-1">Tim Kandang</label>
                    <select id="tim_kandang" name="tim_kandang" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition cursor-pointer">
                        <option value="">-- Pilih Tim Kandang --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team }}" {{ old('tim_kandang', $match->tim_kandang ?? '') == $team ? 'selected' : '' }}>{{ $team }}</option>
                        @endforeach
                    </select>
                    @error('tim_kandang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal --}}
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input id="tanggal" type="date" name="tanggal"
                           value="{{ old('tanggal', isset($match) ? $match->tanggal->format('Y-m-d') : '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition">
                    @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Jam --}}
                <div>
                    <label for="jam" class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                    <input id="jam" type="time" name="jam"
                           value="{{ old('jam', $match->jam ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition">
                    @error('jam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Venue --}}
                <div>
                    <label for="venue" class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                    <select id="venue" name="venue" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition cursor-pointer">
                        <option value="">-- Pilih Venue --</option>
                        @foreach($venues as $v)
                            <option value="{{ $v }}" {{ old('venue', $match->venue ?? '') == $v ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('venue') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Harga --}}
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga Tiket (Rp)</label>
                    <input id="harga" type="number" name="harga"
                           value="{{ old('harga', $match->harga ?? 50000) }}" required min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition">
                    @error('harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-1">Kapasitas</label>
                    <input id="kapasitas" type="number" name="kapasitas"
                           value="{{ old('kapasitas', $match->kapasitas ?? 100) }}" required min="1"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ikl-500 transition">
                    @error('kapasitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer px-4 py-2.5">
                        <input type="checkbox" name="aktif" value="1"
                               {{ old('aktif', $match->aktif ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-ikl-600 border-gray-300 rounded focus:ring-ikl-500">
                        <span class="text-sm font-medium text-gray-700">Pertandingan Aktif</span>
                    </label>
                </div>
            </div>

            {{-- Error validasi client-side --}}
            <div id="match-form-errors" class="hidden mt-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3"></div>

            {{-- Tombol Submit --}}
            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                        class="px-6 py-2.5 bg-ikl-600 text-white text-sm font-semibold rounded-lg hover:bg-ikl-700 transition">
                    {{ isset($match) ? 'Update' : 'Simpan' }}
                </button>
                @if(isset($match))
                    <a href="{{ route('matches.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- ===== DAFTAR PERTANDINGAN ===== --}}
<section class="py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-lg font-bold text-gray-800 mb-4">📋 Semua Pertandingan ({{ $matches->count() }})</h2>

        @if($matches->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <p class="text-gray-500 text-sm">Belum ada data pertandingan.</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Pertandingan</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Venue</th>
                                <th class="text-right px-4 py-3 font-semibold text-gray-600">Harga</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600">Kapasitas</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($matches as $m)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $m->tim_tamu }} vs {{ $m->tim_kandang }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $m->tanggal->format('d M Y') }} · {{ $m->jam }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $m->venue }}</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-800">
                                    Rp {{ number_format($m->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">{{ $m->kapasitas }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $m->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $m->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('matches.edit', $m) }}"
                                           class="text-ikl-600 hover:text-ikl-800 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('matches.destroy', $m) }}" method="POST" class="form-delete inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
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
// JavaScript: Validasi Form Pertandingan Client-Side (DOM Manipulation)
// =====================================================================
(function() {
    var form = document.getElementById('match-form');
    var errorsDiv = document.getElementById('match-form-errors');

    form.addEventListener('submit', function(e) {
        var errors = [];

        // Validasi tim tamu
        if (!document.getElementById('tim_tamu').value.trim()) {
            errors.push('Nama tim tamu wajib diisi.');
        }

        // Validasi tim kandang
        if (!document.getElementById('tim_kandang').value.trim()) {
            errors.push('Nama tim kandang wajib diisi.');
        }

        // Validasi tanggal
        if (!document.getElementById('tanggal').value) {
            errors.push('Tanggal pertandingan wajib diisi.');
        }

        // Validasi jam
        if (!document.getElementById('jam').value) {
            errors.push('Jam pertandingan wajib diisi.');
        }

        // Validasi venue
        if (!document.getElementById('venue').value.trim()) {
            errors.push('Venue wajib diisi.');
        }

        // Validasi harga
        var harga = parseFloat(document.getElementById('harga').value);
        if (isNaN(harga) || harga < 0) {
            errors.push('Harga tiket tidak valid.');
        }

        // Validasi kapasitas
        var kapasitas = parseInt(document.getElementById('kapasitas').value);
        if (isNaN(kapasitas) || kapasitas < 1) {
            errors.push('Kapasitas harus minimal 1.');
        }

        // Tampilkan error
        if (errors.length > 0) {
            e.preventDefault();
            errorsDiv.innerHTML = errors.map(function(err) { return '<p>• ' + err + '</p>'; }).join('');
            errorsDiv.classList.remove('hidden');
            errorsDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            errorsDiv.classList.add('hidden');
        }
    });
})();
</script>
@endpush

