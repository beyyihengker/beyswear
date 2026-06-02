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
        Schema::table('detail_transaksi', function (Blueprint $table) {

            // Menyimpan nama produk saat transaksi terjadi.
            // Data ini disimpan sebagai snapshot sehingga riwayat transaksi
            // tetap konsisten meskipun nama produk berubah di kemudian hari.
            if (!Schema::hasColumn('detail_transaksi', 'produk')) {
                $table->string('produk')->nullable();
            }

            // Menyimpan ukuran yang dipilih saat transaksi.
            if (!Schema::hasColumn('detail_transaksi', 'ukuran')) {
                $table->string('ukuran')->nullable();
            }

            // Menyimpan warna yang dipilih saat transaksi.
            if (!Schema::hasColumn('detail_transaksi', 'warna')) {
                $table->string('warna')->nullable();
            }

            // Digunakan untuk mendukung transaksi multi-item.
            // Kolom jumlah lama dipertahankan untuk kompatibilitas data sebelumnya.
            if (!Schema::hasColumn('detail_transaksi', 'qty')) {
                $table->integer('qty')->default(1);
            }

            // Menyimpan harga produk saat transaksi dilakukan.
            // Harga disimpan langsung agar riwayat transaksi tidak berubah
            // ketika harga produk diperbarui.
            if (!Schema::hasColumn('detail_transaksi', 'harga')) {
                $table->decimal('harga', 12, 2)->default(0);
            }

            // Menyimpan hasil perhitungan harga × qty pada saat transaksi.
            if (!Schema::hasColumn('detail_transaksi', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {

            // Menghapus kolom tambahan yang digunakan
            // untuk fitur transaksi multi-item.
            $table->dropColumn([
                'produk',
                'ukuran',
                'warna',
                'qty',
                'harga',
                'subtotal'
            ]);
        });
    }
};