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
        Schema::table('transaksis', function (Blueprint $table) {

            // Menyimpan nama produk secara langsung pada transaksi.
            // Digunakan pada versi awal sistem ketika satu transaksi
            // hanya dapat berisi satu produk.
            //
            // Pada implementasi terbaru, data produk transaksi
            // disimpan pada tabel detail_transaksi untuk mendukung
            // transaksi multi-item.
            $table->string('produk');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            //
        });
    }
};