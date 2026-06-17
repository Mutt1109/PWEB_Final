<?php

namespace App\Http\Controllers;

use App\Models\IklMatch;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * DashboardController
 * Mengelola halaman utama user: menampilkan jadwal pertandingan,
 * booking tiket, dan CRUD tiket milik user yang sedang login.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     * Berisi: daftar pertandingan aktif, tiket milik user, statistik.
     */
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('matches.index');
        }

        $userId = auth()->id();

        // Ambil tiket milik user yang login (beserta data pertandingan)
        $tickets = Ticket::with('match')
                         ->where('user_id', $userId)
                         ->latest()
                         ->get();

        // Ambil pertandingan yang aktif (untuk form booking)
        $matches = IklMatch::where('aktif', true)
                           ->orderBy('tanggal')
                           ->get();

        // Statistik sederhana
        $totalBooking = $tickets->count();
        $bookingAktif = $tickets->where('status', 'Aktif')->count();

        return view('dashboard', compact('tickets', 'matches', 'totalBooking', 'bookingAktif'));
    }

    /**
     * Simpan booking tiket baru (CREATE).
     * Validasi input, hitung total harga, generate kode booking.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'match_id'     => 'required|exists:matches,id',
            'nama_pemesan' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'jumlah'       => 'required|integer|min:1|max:10',
        ]);

        // Ambil data pertandingan untuk hitung total harga
        $match = IklMatch::findOrFail($validated['match_id']);

        // Simpan tiket ke database
        Ticket::create([
            'match_id'     => $validated['match_id'],
            'user_id'      => auth()->id(),
            'nama_pemesan' => $validated['nama_pemesan'],
            'email'        => $validated['email'],
            'no_hp'        => $validated['no_hp'] ?? null,
            'jumlah'       => $validated['jumlah'],
            'total_harga'  => $match->harga * $validated['jumlah'],
            'kode_booking' => 'TIK-' . strtoupper(Str::random(6)),
        ]);

        return redirect()->route('dashboard')->with('success', 'Tiket berhasil dipesan!');
    }

    /**
     * Membatalkan pesanan tiket oleh user (DELETE).
     */
    public function destroy(Ticket $ticket)
    {
        // Pastikan hanya pemilik tiket yang bisa membatalkannya
        if ($ticket->user_id === auth()->id()) {
            $ticket->delete();
            return redirect()->route('dashboard')->with('success', 'Booking tiket berhasil dibatalkan.');
        }

        abort(403, 'Unauthorized action.');
    }
}
