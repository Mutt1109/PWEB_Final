<?php

namespace App\Http\Controllers;

use App\Models\IklMatch;
use Illuminate\Http\Request;

/**
 * MatchController
 * Mengelola CRUD pertandingan (hanya untuk Admin).
 * Admin bisa menambah, melihat, mengedit, dan menghapus data pertandingan.
 */
class MatchController extends Controller
{
    /**
     * Menampilkan daftar semua pertandingan (READ).
     */
    public function index()
    {
        $matches = IklMatch::orderBy('tanggal', 'desc')->get();

        return view('matches.index', compact('matches'));
    }

    /**
     * Menampilkan form tambah pertandingan baru.
     */
    public function create()
    {
        return view('matches.index', [
            'matches' => IklMatch::orderBy('tanggal', 'desc')->get(),
            'showForm' => true,
        ]);
    }

    /**
     * Simpan pertandingan baru ke database (CREATE).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tim_tamu'    => 'required|string|max:100',
            'tim_kandang' => 'required|string|max:100',
            'tanggal'     => 'required|date',
            'jam'         => 'required|string|max:10',
            'venue'       => 'required|string|max:200',
            'harga'       => 'required|numeric|min:0',
            'kapasitas'   => 'required|integer|min:1',
            'aktif'       => 'boolean',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        IklMatch::create($validated);

        return redirect()->route('matches.index')->with('success', 'Pertandingan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail pertandingan (READ single).
     */
    public function show(IklMatch $match)
    {
        return redirect()->route('matches.index');
    }

    /**
     * Menampilkan form edit pertandingan.
     */
    public function edit(IklMatch $match)
    {
        $matches = IklMatch::orderBy('tanggal', 'desc')->get();

        return view('matches.index', compact('matches', 'match'));
    }

    /**
     * Update data pertandingan di database (UPDATE).
     */
    public function update(Request $request, IklMatch $match)
    {
        $validated = $request->validate([
            'tim_tamu'    => 'required|string|max:100',
            'tim_kandang' => 'required|string|max:100',
            'tanggal'     => 'required|date',
            'jam'         => 'required|string|max:10',
            'venue'       => 'required|string|max:200',
            'harga'       => 'required|numeric|min:0',
            'kapasitas'   => 'required|integer|min:1',
            'aktif'       => 'boolean',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $match->update($validated);

        return redirect()->route('matches.index')->with('success', 'Pertandingan berhasil diperbarui!');
    }

    /**
     * Hapus pertandingan dari database (DELETE).
     */
    public function destroy(IklMatch $match)
    {
        $match->delete();

        return redirect()->route('matches.index')->with('success', 'Pertandingan berhasil dihapus.');
    }
}
