<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    // Menentukan nama tabel yang digunakan model ini
    // Secara default Laravel akan mencari tabel "detail_transaksis"
    // karena nama model = DetailTransaksi
    // Maka perlu ditentukan manual menjadi "detail_transaksi"
    protected $table = 'detail_transaksi';

    // Field yang boleh diisi menggunakan create() atau update()
    // Tujuannya untuk mencegah mass assignment error
    protected $fillable = [

        // Foreign key ke tabel transaksis
        'transaksi_id',

        // Foreign key ke tabel produks
        'produk_id',

        // Nama produk
        // Disimpan juga sebagai backup agar data transaksi tetap ada
        // meskipun nama produk berubah di masa depan
        'produk',

        // Ukuran produk
        'ukuran',

        // Warna produk
        'warna',

        // Jumlah item yang dibeli
        'qty',

        // Backup qty dari sistem lama
        'jumlah',

        // Harga satuan produk saat transaksi
        // Disimpan agar harga lama transaksi tidak ikut berubah
        // jika harga produk diubah di masa depan
        'harga',

        // Total harga item
        // hasil dari qty × harga
        'subtotal',
    ];

    public function transaksi()
    {
        // Relasi many-to-one
        // Banyak detail transaksi dimiliki oleh satu transaksi
        return $this->belongsTo(Transaksi::class);
    }

    public function produkData()
    {
        // Relasi many-to-one
        // Banyak detail transaksi mengarah ke satu produk

        // 'produk_id' adalah foreign key yang dipakai
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}