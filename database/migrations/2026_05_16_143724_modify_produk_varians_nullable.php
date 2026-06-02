<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk_varians', function (Blueprint $table) {

            // Pada implementasi awal ukuran dan warna wajib diisi.
            // Perubahan ini memungkinkan produk memiliki varian
            // yang hanya memiliki ukuran, hanya memiliki warna,
            // atau bahkan salah satunya kosong.
            $table->string('ukuran')->nullable()->change();

            $table->string('warna')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('produk_varians', function (Blueprint $table) {

            // Mengembalikan aturan lama dimana ukuran dan warna wajib diisi.
            $table->string('ukuran')->nullable(false)->change();

            $table->string('warna')->nullable(false)->change();
        });
    }
};