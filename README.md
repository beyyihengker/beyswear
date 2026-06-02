# BeysWear Fashion Management System

BeysWear Fashion Management System adalah aplikasi web berbasis Laravel yang digunakan untuk membantu pengelolaan toko fashion, mulai dari manajemen produk, transaksi penjualan, stok varian produk, hingga laporan penjualan.

## Fitur Utama

- Login dan autentikasi user
- Role admin dan kasir
- CRUD produk
- Upload foto produk
- Auto generate kode barang
- Manajemen varian produk
- Sistem transaksi multi-item
- Cetak struk transaksi
- Dashboard statistik
- Laporan penjualan mingguan dan bulanan
- Katalog customer
- Live search menggunakan AJAX
- Dark mode dan preferensi tampilan
- Soft delete produk

## Teknologi yang Digunakan

- Laravel 13
- PHP 8
- MySQL
- HTML5
- CSS3
- JavaScript
- AJAX / Fetch API

## Cara Instalasi

```bash
git clone https://github.com/username/beyswear.git
cd beyswear
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

Aplikasi dapat diakses melalui:

```txt
http://127.0.0.1:8000
```

## Akun Login

### Admin

```txt
Email    : berlianaprilly24@gmail.com
Password : berli123
```

### Kasir

```txt
Email    : admin123@gmail.com
Password : admin123
```

## Struktur Database

Tabel utama:

* users
* produks
* produk_varians
* transaksis
* detail_transaksi

## Developer

BeysWear Fashion Management System
Dikembangkan untuk memenuhi tugas Pemrograman Web.