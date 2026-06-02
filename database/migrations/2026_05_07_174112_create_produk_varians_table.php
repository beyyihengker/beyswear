<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_varians', function (Blueprint $table) {

            $table->id();

            // Setiap varian harus terhubung ke satu produk.
            // Penghapusan produk akan menghapus seluruh variannya.
            $table->foreignId('produk_id')
                ->constrained()
                ->onDelete('cascade');

            // Ukuran dan warna dipisahkan ke tabel varian
            // agar satu produk dapat memiliki banyak kombinasi
            // ukuran dan warna dengan stok yang berbeda.
            $table->enum('ukuran', ['S','M','L','XL']);

            $table->string('warna');

            // Stok disimpan pada level varian, bukan produk.
            // Contoh: Kaos Hitam M dan Kaos Hitam L dapat memiliki
            // jumlah stok yang berbeda.
            $table->integer('stok');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_varians');
    }
};