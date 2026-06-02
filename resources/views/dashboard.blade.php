@extends('layouts.app')

@section('title', 'Dashboard — BeysWear Fashion')

@section('content')

<div class="container-fluid">

    {{-- Ringkasan statistik utama yang dihitung dari DashboardController --}}
    <div class="statistik-grid">

        <x-stat-card judul="Total Item" :nilai="$statistik['totalItem']" />

        <x-stat-card
            judul="Total Penjualan"
            nilai="Rp {{ number_format($statistik['totalPenjualan']) }}"
            warna="#3C507D"
        />

        <x-stat-card
            judul="Stok Menipis"
            :nilai="$statistik['stokMenipis']"
            warna="#c0392b"
        />

        <x-stat-card
            judul="Total Terjual"
            :nilai="$statistik['totalTerjual']"
        />

    </div>

    {{-- Daftar varian produk dengan stok kurang dari 5 pcs --}}
    <section class="tabel-box">

        <div class="tabel-header">
            <h3>Daftar Stok Menipis</h3>
            <span class="chip">Stok kurang dari 5 pcs</span>
        </div>

        <div class="tabel-scroll">

            <table>

                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Warna</th>
                        <th>Stok</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($stokMenipisList as $varian)

                        <tr>

                            <td>{{ $varian->produk->nama ?? '-' }}</td>
                            <td>{{ $varian->ukuran ?: '-' }}</td>
                            <td>{{ $varian->warna ?: '-' }}</td>
                            <td>{{ $varian->stok }} pcs</td>

                            <td class="text-center">

                                {{-- Status stok berdasarkan jumlah stok saat ini --}}
                                @if($varian->stok == 0)

                                    <span class="chip status-badge" style="color:#c0392b;">
                                        Habis
                                    </span>

                                @else

                                    <span class="chip status-badge" style="color:#d35400;">
                                        Menipis
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="text-align:center;">
                                Tidak ada stok menipis.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

    {{-- Menampilkan 5 transaksi terbaru --}}
    <section class="tabel-box">

        <div class="tabel-header">
            <h3>Daftar Penjualan Terbaru</h3>
            <span class="chip">5 transaksi terakhir</span>
        </div>

        <div class="tabel-scroll">

            <table>

                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Warna</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transaksi as $t)

                    <tr>

                        <td>{{ $t->kode_transaksi }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}
                        </td>

                        <td class="multi-item">

                            {{--
                                Mendukung transaksi multi-item dari tabel detail_transaksi.
                                Jika transaksi lama tidak memiliki detail, gunakan data lama.
                            --}}
                            @if($t->details->count() > 0)

                                @foreach($t->details as $d)
                                    <div>{{ $d->produk }}</div>
                                @endforeach

                            @else

                                {{ $t->produk ?? '-' }}

                            @endif

                        </td>

                        <td class="multi-item">

                            @if($t->details->count() > 0)

                                @foreach($t->details as $d)
                                    <div>{{ $d->ukuran ?? '-' }}</div>
                                @endforeach

                            @else

                                {{ $t->ukuran ?? '-' }}

                            @endif

                        </td>

                        <td class="multi-item">

                            @if($t->details->count() > 0)

                                @foreach($t->details as $d)
                                    <div>{{ $d->warna ?? '-' }}</div>
                                @endforeach

                            @else

                                {{ $t->warna ?? '-' }}

                            @endif

                        </td>

                        <td class="multi-item">

                            @if($t->details->count() > 0)

                                @foreach($t->details as $d)
                                    <div>{{ $d->qty ?? $d->jumlah }}</div>
                                @endforeach

                            @else

                                {{ $t->qty ?? '-' }}

                            @endif

                        </td>

                        <td>
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </td>

                        <td style="text-align:center;">
                            {{ $t->pembayaran }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" style="text-align:center;">
                            Belum ada data transaksi.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

    {{-- Menampilkan 2 produk dengan jumlah penjualan tertinggi --}}
    <section class="tabel-box">

        <div class="tabel-header">
            <h3>Produk Terlaris</h3>
            <span class="chip">Top 2 produk</span>
        </div>

        <div class="tabel-scroll">

            <table>

                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Total Terjual</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($produkTerlaris as $p)

                    <tr>

                        <td>{{ $p['nama'] }}</td>
                        <td>{{ $p['kategori'] ?? '-' }}</td>

                        <td style="text-align:center;">
                            {{ $p['terjual'] }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="3" style="text-align: center;">
                            Data produk terlaris belum tersedia.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

{{-- Data trend fashion berasal dari API eksternal yang dipanggil melalui JavaScript --}}
<section class="form-box dashboard-section">

    <div class="section-heading">

        <div class="section-title-wrap">

            <div>
                <h3>Trend Fashion</h3>
                <p>Produk yang sedang banyak diminati</p>
            </div>

        </div>

    </div>

    <div id="trendContainer">
        <div class="loading-box">Loading produk...</div>
    </div>

</section>

{{-- Statistik kunjungan dashboard yang disimpan menggunakan session Laravel --}}
<section class="form-box dashboard-section">

    <div class="section-heading">

        <div class="section-title-wrap">

            <div>
                <h3>Aktivitas Dashboard POS</h3>
                <p>Statistik kunjungan dashboard</p>
            </div>

        </div>

        <form action="{{ route('reset.session') }}" method="POST">

            @csrf

            {{-- Menghapus data statistik kunjungan dari session --}}
            <button class="btn btn-primer reset-btn">
                Reset Hitungan
            </button>

        </form>

    </div>

    <div class="visit-grid">

        <div class="visit-card">

            <div class="visit-icon">👥</div>

            <div>
                <p>Total Kunjungan</p>
                <h2>{{ $visit }}</h2>
                <span>Jumlah total kunjungan dashboard</span>
            </div>

        </div>

        <div class="visit-card">

            <div class="visit-icon green">📅</div>

            <div>
                <p>Kunjungan Pertama</p>
                <h2>{{ \Carbon\Carbon::parse($first)->format('d M Y') }}</h2>
                <span>{{ \Carbon\Carbon::parse($first)->format('H:i:s') }}</span>
            </div>

        </div>

        <div class="visit-card">

            <div class="visit-icon purple">📅</div>

            <div>
                <p>Kunjungan Terakhir</p>
                <h2>{{ \Carbon\Carbon::parse($last)->format('d M Y') }}</h2>
                <span>{{ \Carbon\Carbon::parse($last)->format('H:i:s') }}</span>
            </div>

        </div>

    </div>

</section>

@push('scripts')

{{-- Script tambahan khusus halaman dashboard --}}
<script>
    console.log("Dashboard BeysWear Berhasil Dimuat!");
</script>

@endpush

@endsection