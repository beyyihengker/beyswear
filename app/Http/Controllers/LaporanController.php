<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil bulan dari request
        // Jika kosong, otomatis menggunakan bulan sekarang
        $bulan = $request->bulan ?? date('m');

        // Mengambil tahun dari request
        // Jika kosong, otomatis menggunakan tahun sekarang
        $tahun = $request->tahun ?? date('Y');

        // Cara lain mengambil input request dengan default value
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Query dasar transaksi berhasil berdasarkan bulan dan tahun
        $query = Transaksi::query()
            ->where('status', 'berhasil')

            // Filter berdasarkan bulan
            ->where(DB::raw('MONTH(created_at)'), '=', $bulan)

            // Filter berdasarkan tahun
            ->where(DB::raw('YEAR(created_at)'), '=', $tahun);

        // Menjalankan query
        $filtered = $query->get();

        // Menghitung jumlah transaksi
        $jumlahTransaksi = $filtered->count();

        // Menghitung total omzet
        $totalOmzet = $filtered->sum('total_harga');

        // Membuat laporan mingguan
        $laporanMingguan = Transaksi::query()
            ->where('status', 'berhasil')

            // Select custom menggunakan SQL raw
            ->select(

                // Membagi minggu berdasarkan tanggal
                // Contoh:
                // tanggal 1-7   = minggu 1
                // tanggal 8-14 = minggu 2
                DB::raw('LEAST(CEIL(DAY(created_at) / 7), 4) as mingguke'),

                // Menghitung jumlah transaksi tiap minggu
                DB::raw('COUNT(*) as jumlahTransaksi'),

                // Menghitung total omzet tiap minggu
                DB::raw('SUM(total_harga) as omzetMingguan')
            )

            // Filter bulan
            ->whereMonth('created_at', $bulan)

            // Filter tahun
            ->whereYear('created_at', $tahun)

            // Group berdasarkan minggu
            ->groupBy('mingguke')

            // Urutkan minggu dari kecil ke besar
            ->orderBy('mingguke')

            ->get()

            // Menambahkan produk terlaris dan kurang laris per minggu
            ->map(function ($minggu) use ($bulan, $tahun) {

                // Query mengambil produk per minggu
                $produkMingguan = DB::table('detail_transaksi')

                    // Join detail_transaksi dengan transaksis
                    ->join(
                        'transaksis',
                        'detail_transaksi.transaksi_id',
                        '=',
                        'transaksis.id'
                    )

                    // Hanya transaksi berhasil
                    ->where('transaksis.status', 'berhasil')

                    // Filter bulan
                    ->whereMonth('transaksis.created_at', $bulan)

                    // Filter tahun
                    ->whereYear('transaksis.created_at', $tahun)

                    // Filter minggu tertentu
                    ->whereRaw(
                        'LEAST(CEIL(DAY(transaksis.created_at) / 7), 4) = ?',
                        [$minggu->mingguke]
                    )

                    // Mengambil nama produk + total qty terjual
                    ->select(
                        'detail_transaksi.produk',
                        DB::raw('SUM(detail_transaksi.qty) as total_qty')
                    )

                    // Group berdasarkan nama produk
                    ->groupBy('detail_transaksi.produk')

                    ->get();

                // Mengambil produk paling laris minggu tersebut
                $minggu->produkTerlaris = $produkMingguan
                    ->sortByDesc('total_qty')
                    ->first()
                    ->produk ?? '-';

                // Mengambil produk paling sedikit terjual minggu tersebut
                $minggu->produkKurang = $produkMingguan
                    ->sortBy('total_qty')
                    ->first()
                    ->produk ?? '-';

                return $minggu;
            });

        // Membuat laporan bulanan untuk chart/grafik
        $laporanBulanan = Transaksi::query()
            ->where('status', 'berhasil')

            ->select(

                // Mengambil nomor bulan
                DB::raw('MONTH(created_at) as bulan_num'),

                // Total omzet per bulan
                DB::raw('SUM(total_harga) as total_omzet'),

                // Jumlah transaksi per bulan
                DB::raw('COUNT(*) as jumlah_transaksi')
            )

            // Filter tahun
            ->where(DB::raw('YEAR(created_at)'), '=', $tahun)

            // Group berdasarkan bulan
            ->groupBy('bulan_num')

            // Urutkan bulan
            ->orderBy('bulan_num')

            ->get()

            // Mengubah key collection menjadi nomor bulan
            // Contoh:
            // 1 => Januari
            // 2 => Februari
            ->keyBy('bulan_num');

        // Array nama bulan untuk tampilan chart/laporan
        $namaBulan = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des'
        ];

        // Membuat list tahun untuk dropdown filter
        // Contoh:
        // 2026, 2025, 2024, 2023
        $tahunList = range(date('Y'), date('Y') - 3);

        // Mengirim semua data ke view laporan
        return view('laporan', compact(
            'laporanMingguan',
            'jumlahTransaksi',
            'totalOmzet',
            'bulan',
            'tahun',
            'laporanBulanan',
            'namaBulan',
            'tahunList'
        ));
    }
}