<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\ProdukVarian;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Menampilkan alert welcome hanya sekali selama session aktif
        if (!session()->has('welcome_flash')) {
            session()->flash('success', 'Selamat datang kembali!');
            session(['welcome_flash' => true]);
        }

        // Mengambil jumlah kunjungan dashboard dari session
        // Jika belum ada, default = 0
        $count = session('visit_count', 0);

        // Menyimpan jumlah kunjungan terbaru
        // serta waktu terakhir membuka dashboard
        session([
            'visit_count' => $count + 1,
            'last_visit' => now(),
        ]);

        // Menyimpan waktu pertama kali user membuka dashboard
        // Hanya dijalankan sekali selama session
        if (!session()->has('first_visit')) {
            session([
                'first_visit' => now(),
            ]);
        }

        // Menghitung total barang terjual dari sistem transaksi baru
        // Data diambil dari tabel detail_transaksi
        $totalTerjualBaru = DetailTransaksi::query()
            ->whereHas('transaksi', function ($q) {

                // Hanya menghitung transaksi yang berhasil
                $q->where('status', 'berhasil');
            })

            // Menjumlahkan seluruh qty
            ->sum('qty');

        // Menghitung total barang terjual dari sistem transaksi lama
        // Dipakai jika masih ada data lama yang menyimpan qty langsung di tabel transaksis
        $totalTerjualLama = Transaksi::query()
            ->where('status', 'berhasil')

            // Pastikan kolom qty tidak kosong
            ->whereNotNull('qty')

            // Menjumlahkan qty
            ->sum('qty');

        // Data statistik dashboard
        $statistik = [

            // Total seluruh produk
            'totalItem' => Produk::query()->count(),

            // Total omzet transaksi berhasil
            'totalPenjualan' => Transaksi::query()
                ->where('status', 'berhasil')
                ->sum('total_harga'),

            // Menghitung jumlah varian dengan stok di bawah 5
            'stokMenipis' => ProdukVarian::query()
                ->where('stok', '<', 5)
                ->count(),

            // Menggabungkan total barang terjual lama + baru
            'totalTerjual' => $totalTerjualBaru + $totalTerjualLama,
        ];

        // Mengambil 5 transaksi terbaru untuk dashboard
        // with('details') digunakan agar detail transaksi ikut diambil
        $transaksi = Transaksi::with('details')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Mengambil produk terlaris dari sistem transaksi baru
        $produkTerlarisBaru = DetailTransaksi::query()

            // Mengambil nama produk + total qty terjual
            ->select('produk', DB::raw('SUM(qty) as terjual'))

            // Hanya transaksi berhasil
            ->whereHas('transaksi', function ($q) {
                $q->where('status', 'berhasil');
            })

            // Group berdasarkan nama produk
            ->groupBy('produk')

            ->get();

        // Mengambil produk terlaris dari sistem transaksi lama
        $produkTerlarisLama = Transaksi::query()
            ->select('produk', DB::raw('SUM(qty) as terjual'))
            ->where('status', 'berhasil')

            // Pastikan nama produk tidak null
            ->whereNotNull('produk')

            ->groupBy('produk')

            ->get();

        // Menggabungkan data produk lama dan baru
        $produkTerlaris = $produkTerlarisBaru

            // Menggabungkan collection
            ->concat($produkTerlarisLama)

            // Group ulang berdasarkan nama produk
            ->groupBy('produk')

            // Mengubah format data hasil query
            ->map(function ($items, $namaProduk) {

                // Mencari kategori produk berdasarkan nama produk
                $produk = Produk::query()
                    ->where('nama', '=', $namaProduk)
                    ->first();

                return [

                    // Nama produk
                    'nama' => $namaProduk,

                    // Jika produk ditemukan tampilkan kategori
                    // Jika tidak ditemukan tampilkan "-"
                    'kategori' => $produk ? $produk->kategori : '-',

                    // Menjumlahkan total penjualan produk
                    'terjual' => $items->sum('terjual'),
                ];
            })

            // Urutkan dari yang paling banyak terjual
            ->sortByDesc('terjual')

            // Ambil 2 produk terlaris
            ->take(2)

            // Reset index collection
            ->values();

        // Mengambil daftar produk dengan stok menipis
        $stokMenipisList = ProdukVarian::with('produk')

            // Filter stok kurang dari 5
            ->where('stok', '<', 5)

            // Urutkan dari stok paling sedikit
            ->orderBy('stok', 'asc')

            // Ambil maksimal 5 data
            ->take(5)

            ->get();

        // Mengirim semua data ke halaman dashboard
        return view('dashboard', [
            'statistik'      => $statistik,
            'transaksi'      => $transaksi,
            'produkTerlaris' => $produkTerlaris,
            'stokMenipisList' => $stokMenipisList,

            // Data session
            'visit'          => session('visit_count'),
            'first'          => session('first_visit'),
            'last'           => session('last_visit'),
        ]);
    }
}