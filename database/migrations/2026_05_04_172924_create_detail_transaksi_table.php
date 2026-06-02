<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Membuat tabel detail_transaksi
        // Tabel ini berfungsi sebagai penghubung antara
        // tabel transaksis dan tabel produks
        //
        // Setiap baris mewakili satu produk yang dibeli
        // dalam suatu transaksi
        Schema::create('detail_transaksi', function (Blueprint $table) {

            // Primary key auto increment
            $table->id();

            // Foreign key ke tabel produks
            // Menunjukkan produk apa yang dibeli
            //
            // constrained() otomatis membuat relasi ke:
            // produks.id
            //
            // onDelete('cascade') berarti:
            // jika produk dihapus maka detail transaksi terkait ikut terhapus
            $table->foreignId('produk_id')
                ->constrained()
                ->onDelete('cascade');

            // Foreign key ke tabel transaksis
            // Menunjukkan detail ini milik transaksi yang mana
            //
            // constrained() otomatis membuat relasi ke:
            // transaksis.id
            //
            // onDelete('cascade') berarti:
            // jika transaksi dihapus maka detail transaksi ikut terhapus
            $table->foreignId('transaksi_id')
                ->constrained()
                ->onDelete('cascade');

            // Jumlah produk yang dibeli
            // Contoh:
            // Kaos Oversize = 3 pcs
            $table->integer('jumlah');

            // created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel saat migration di-rollback
        Schema::dropIfExists('detail_transaksi');
    }
};