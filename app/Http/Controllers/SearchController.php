<?php

namespace App\Http\Controllers;

use App\Models\IklMatch;
use Illuminate\Http\Request;

/**
 * SearchController
 * Menangani pencarian pertandingan secara asinkron (AJAX/Fetch API).
 * Mengembalikan response JSON tanpa reload halaman penuh.
 */
class SearchController extends Controller
{
    /**
     * Mencari pertandingan berdasarkan keyword.
     * Dipanggil via Fetch API (POST) dari JavaScript di sisi klien.
     * Mengembalikan JSON response.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        // Minimal 2 karakter untuk mulai mencari
        if (strlen($query) < 2) {
            return response()->json([
                'data'    => [],
                'message' => 'Ketik minimal 2 karakter',
            ]);
        }

        // Cari pertandingan berdasarkan nama tim atau venue
        $matches = IklMatch::where(function ($q) use ($query) {
                        $q->where('tim_tamu',    'like', "%{$query}%")
                          ->orWhere('tim_kandang', 'like', "%{$query}%")
                          ->orWhere('venue',       'like', "%{$query}%");
                    })
                    ->where('aktif', true)
                    ->orderBy('tanggal')
                    ->limit(10)
                    ->get(['id', 'tim_tamu', 'tim_kandang', 'tanggal', 'jam', 'venue', 'harga']);

        return response()->json([
            'data'  => $matches,
            'count' => $matches->count(),
            'query' => $query,
        ]);
    }
}
