<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Membuat tabel 'tickets' untuk menyimpan data pemesanan tiket
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_pemesan');        // Nama pemesan tiket
            $table->string('email');                // Email pemesan
            $table->string('no_hp')->nullable();    // Nomor HP (opsional)
            $table->integer('jumlah');               // Jumlah tiket yang dipesan
            $table->decimal('total_harga', 10, 2);   // Total harga
            $table->enum('status', ['Aktif', 'Terpakai', 'Dibatalkan'])->default('Aktif');
            $table->string('kode_booking')->unique(); // Kode booking unik
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
