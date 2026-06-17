@extends('layouts.app')
@section('title', 'Kelola Pesanan Tiket — IKL Ticket Hub')
@section('content')

{{-- ===== HEADER ===== --}}
<section class="bg-gradient-to-r from-ikl-600 to-ikl-800 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-white">Kelola Pesanan Tiket</h1>
        <p class="text-ikl-200 text-sm mt-1">Daftar semua tiket yang dipesan oleh user</p>
    </div>
</section>

{{-- ===== DAFTAR SEMUA TIKET ===== --}}
<section class="py-8 px-4">
    <div class="max-w-6xl mx-auto">

        @if($tickets->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <p class="text-gray-500 text-sm">Belum ada user yang memesan tiket.</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
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

                                {{-- Status (UPDATE) --}}
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <select name="status" onchange="this.form.submit()"
                                                class="text-xs font-semibold px-2 py-1 rounded-full border-0 cursor-pointer focus:ring-0
                                                {{ $ticket->status === 'Aktif' ? 'bg-green-100 text-green-700' : ($ticket->status === 'Terpakai' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                            <option value="Aktif" {{ $ticket->status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Terpakai" {{ $ticket->status === 'Terpakai' ? 'selected' : '' }}>Terpakai</option>
                                            <option value="Dibatalkan" {{ $ticket->status === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </form>
                                </td>

                                {{-- Hapus (DELETE) --}}
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="form-delete inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
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
