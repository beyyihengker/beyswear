<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {

            // Mengaktifkan Soft Delete pada produk.
            //
            // Produk yang dihapus tidak langsung hilang dari database,
            // melainkan ditandai melalui kolom deleted_at sehingga
            // masih dapat dipulihkan kembali jika diperlukan.
            //
            // Fitur ini digunakan untuk menjaga riwayat transaksi dan
            // mengurangi risiko kehilangan data akibat penghapusan produk.
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {

            // Menghapus dukungan Soft Delete dari tabel produk.
            $table->dropSoftDeletes();
        });
    }
};