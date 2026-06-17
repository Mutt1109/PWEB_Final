<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Model Ticket
 * Merepresentasikan data pemesanan tiket oleh user.
 */
class Ticket extends Model
{
    // Kolom yang boleh diisi massal
    protected $fillable = [
        'match_id',
        'user_id',
        'nama_pemesan',
        'email',
        'no_hp',
        'jumlah',
        'total_harga',
        'status',
        'kode_booking',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    /**
     * Auto-generate kode booking unik saat tiket baru dibuat.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Ticket $ticket) {
            if (empty($ticket->kode_booking)) {
                $ticket->kode_booking = 'TIK-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Relasi: Tiket ini milik satu pertandingan.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(IklMatch::class, 'match_id');
    }

    /**
     * Relasi: Tiket ini milik satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
