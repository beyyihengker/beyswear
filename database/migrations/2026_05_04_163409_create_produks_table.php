<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel produk utama
        // Tabel ini menyimpan informasi umum produk
        // seperti nama, kategori, harga, dan foto
        Schema::create('produks', function (Blueprint $table) {

            // Primary key auto increment
            $table->id();

            // Menyimpan lokasi/path foto produk
            // Nullable karena produk boleh dibuat tanpa foto terlebih dahulu
            $table->string('foto')->nullable();

            // Kode produk unik
            // Contoh: BRG001, BRG002
            // Digunakan sebagai identitas produk yang mudah dibaca manusia
            $table->string('kode', 10)->unique();

            // Nama produk
            // Maksimal 100 karakter
            $table->string('nama', 100);

            // Kategori produk
            // Hanya boleh memilih salah satu nilai yang tersedia
            $table->enum('kategori', [
                'Atasan',
                'Bawahan',
                'Dress',
                'Outer / Jaket',
                'Aksesori'
            ]);

            // Harga produk
            // Format:
            // decimal(12,2)
            // Maksimal 12 digit dengan 2 digit di belakang koma
            // Contoh: 150000.00
            $table->decimal('harga', 12, 2);

            // Status ketersediaan produk
            // true  = tersedia
            // false = tidak tersedia
            // Default saat produk dibuat adalah tersedia
            $table->boolean('tersedia')->default(true);

            // created_at dan updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Menghapus tabel saat migration di-rollback
        Schema::dropIfExists('produks');
    }
};
