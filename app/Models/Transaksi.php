<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailTransaksi;

class Transaksi extends Model
{
    // Field yang boleh diisi menggunakan create() atau update()
    protected $fillable = [

        // Kode transaksi otomatis
        // Contoh: TRX-001
        'kode_transaksi',

        // Tanggal transaksi
        'tanggal',

        // Total seluruh harga transaksi
        'total_harga',

        // Metode pembayaran
        // Contoh: Cash atau QRIS
        'pembayaran',

        // Status transaksi
        // Contoh: berhasil atau dibatalkan
        'status',
    ];

    public function details()
    {
        // Relasi one-to-many
        // Satu transaksi bisa memiliki banyak detail transaksi

        // Contoh:
        // TRX-001
        // -> Kaos Hitam qty 2
        // -> Celana Cargo qty 1

        return $this->hasMany(DetailTransaksi::class);
    }
}