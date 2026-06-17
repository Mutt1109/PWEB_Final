<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * AdminTicketController
 * Mengelola semua pesanan tiket (Hanya untuk Admin).
 */
class AdminTicketController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan tiket dari semua user.
     */
    public function index()
    {
        $tickets = Ticket::with(['match', 'user'])->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Update status tiket (UPDATE).
     * Status bisa diubah: Aktif, Terpakai, Dibatalkan.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:Aktif,Terpakai,Dibatalkan',
        ]);

        $ticket->update(['status' => $validated['status']]);

        return back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    /**
     * Hapus tiket (DELETE).
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return back()->with('success', 'Tiket berhasil dihapus.');
    }
}
