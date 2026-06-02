<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {

            // Status digunakan untuk membedakan transaksi yang masih valid
            // dan transaksi yang telah dibatalkan tanpa menghapus data transaksi.
            //
            // Nilai default "berhasil" diberikan agar data transaksi lama
            // tetap dianggap valid setelah kolom ini ditambahkan.
            $table->string('status')->default('berhasil')->after('pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {

            // Menghapus fitur pencatatan status transaksi.
            $table->dropColumn('status');
        });
    }
};