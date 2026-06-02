<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {

            // Menyimpan jumlah item pada transaksi.
            // Kolom ini digunakan pada versi awal sistem ketika
            // satu transaksi hanya dapat berisi satu produk.
            //
            // Pada implementasi terbaru, jumlah item disimpan
            // pada tabel detail_transaksi (kolom qty).
            // Kolom ini dipertahankan untuk kompatibilitas data lama.
            $table->integer('qty')
                  ->default(1)
                  ->after('kode_transaksi');

        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {

            // Menghapus kolom qty saat migration di-rollback.
            $table->dropColumn('qty');

        });
    }
};