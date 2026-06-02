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

            // Kolom-kolom ini digunakan pada desain transaksi lama
            // yang hanya mendukung satu produk per transaksi.
            //
            // Setelah sistem menggunakan detail_transaksi untuk
            // mendukung transaksi multi-item, nilai kolom ini
            // tidak selalu terisi sehingga dibuat nullable untuk
            // menjaga kompatibilitas data lama dan baru.
            $table->string('produk')->nullable()->change();

            $table->string('ukuran')->nullable()->change();

            $table->string('warna')->nullable()->change();

            $table->integer('qty')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {

            // Mengembalikan aturan lama dimana seluruh informasi
            // produk pada tabel transaksis wajib diisi.
            $table->string('produk')->nullable(false)->change();
            $table->string('ukuran')->nullable(false)->change();
            $table->string('warna')->nullable(false)->change();
            $table->integer('qty')->nullable(false)->change();
        });
    }
};