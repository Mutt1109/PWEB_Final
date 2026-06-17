<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model IklMatch
 * Merepresentasikan data pertandingan.
 * Nama class 'IklMatch' karena 'Match' adalah reserved keyword di PHP 8.
 */
class IklMatch extends Model
{
    // Nama tabel di database
    protected $table = 'matches';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'tim_tamu',
        'tim_kandang',
        'tanggal',
        'jam',
        'venue',
        'harga',
        'kapasitas',
        'aktif',
    ];

    // Tipe data kolom
    protected $casts = [
        'tanggal' => 'date',
        'harga'   => 'decimal:2',
        'aktif'   => 'boolean',
    ];

    /**
     * Relasi: Satu pertandingan punya banyak tiket.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'match_id');
    }

    /**
     * Accessor: Nama pertandingan lengkap (misal: "RRQ vs ONIC").
     */
    public function getNamaAttribute(): string
    {
        return "{$this->tim_tamu} vs {$this->tim_kandang}";
    }
}
