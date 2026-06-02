<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan atribut profil pengguna.
     * no_hp digunakan untuk informasi kontak,
     * sedangkan jabatan digunakan untuk kebutuhan tampilan/profil
     * dan tidak berkaitan langsung dengan hak akses sistem.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            // Informasi kontak pengguna
            $table->string('no_hp')->nullable();

            // Jabatan ditampilkan pada profil pengguna.
            // Berbeda dengan role yang digunakan untuk otorisasi/login.
            $table->string('jabatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};