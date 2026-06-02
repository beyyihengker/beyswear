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
        // Membuat tabel users
        // Digunakan untuk menyimpan data akun admin dan kasir
        Schema::create('users', function (Blueprint $table) {

            // Primary key auto increment
            $table->id();

            // Nama pengguna
            $table->string('name');

            // Email harus unik agar tidak ada akun ganda
            $table->string('email')->unique();

            // Digunakan Laravel untuk fitur verifikasi email
            $table->timestamp('email_verified_at')->nullable();

            // Password yang sudah di-hash
            $table->string('password');

            // Token "remember me" saat login
            $table->rememberToken();

            // Hak akses pengguna
            // admin = akses penuh
            // kasir = akses operasional
            $table->enum('role', ['admin', 'kasir'])->default('kasir');

            // created_at dan updated_at
            $table->timestamps();
        });

        // Tabel bawaan Laravel
        // Digunakan untuk fitur reset password
        Schema::create('password_reset_tokens', function (Blueprint $table) {

            // Email dijadikan primary key
            $table->string('email')->primary();

            // Token reset password
            $table->string('token');

            // Waktu token dibuat
            $table->timestamp('created_at')->nullable();
        });

        // Tabel session database
        // Digunakan jika SESSION_DRIVER=database
        Schema::create('sessions', function (Blueprint $table) {

            // ID session
            $table->string('id')->primary();

            // User yang sedang login (boleh null jika guest)
            $table->foreignId('user_id')->nullable()->index();

            // IP address pengguna
            $table->string('ip_address', 45)->nullable();

            // Informasi browser/device pengguna
            $table->text('user_agent')->nullable();

            // Data session Laravel yang diserialisasi
            $table->longText('payload');

            // Timestamp aktivitas terakhir user
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel jika migration di-rollback

        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};