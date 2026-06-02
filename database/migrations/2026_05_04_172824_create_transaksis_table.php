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
        // Membuat tabel transaksi
        // Tabel ini menyimpan informasi utama transaksi penjualan
        // Sedangkan detail barang yang dibeli disimpan di tabel detail_transaksi
        Schema::create('transaksis', function (Blueprint $table) {

            // Primary key auto increment
            $table->id();

            // Kode transaksi unik
            // Contoh: TRX-001, TRX-002
            // Digunakan untuk memudahkan pencarian transaksi
            $table->string('kode_transaksi')->unique();

            // Tanggal transaksi dilakukan
            $table->date('tanggal');

            // Total harga seluruh item dalam transaksi
            // Nilai ini merupakan hasil penjumlahan seluruh subtotal
            // yang ada pada detail_transaksi
            $table->decimal('total_harga', 12, 2);

            // Metode pembayaran yang digunakan
            // Saat ini hanya mendukung Cash dan QRIS
            $table->enum('pembayaran', ['Cash', 'QRIS']);

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
        Schema::dropIfExists('transaksis');
    }
};