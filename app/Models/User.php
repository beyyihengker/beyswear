<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    // HasFactory digunakan untuk factory/seeder Laravel
    // Notifiable digunakan agar user bisa menerima notifikasi Laravel
    use HasFactory, Notifiable;

    // Field yang boleh diisi menggunakan create() atau update()
    protected $fillable = [

        // Nama user
        'name',

        // Email user
        'email',

        // Password user
        'password',

        // Role user
        // Contoh: admin atau kasir
        'role',

        // Nomor HP user
        'no_hp',

        // Jabatan user
        // Di sini sebenarnya tidak disimpan langsung di database
        // karena dibuat otomatis lewat accessor
        'jabatan'
    ];

    public function getJabatanAttribute()
    {
        // Accessor Laravel
        // Digunakan agar bisa memanggil:
        // $user->jabatan

        // Jika role = admin
        // maka jabatan otomatis menjadi Administrator

        // Jika bukan admin
        // maka dianggap Staff Kasir

        return $this->role === 'admin'
            ? 'Administrator'
            : 'Staff Kasir';
    }
}