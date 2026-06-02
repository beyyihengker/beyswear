<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <title>
        Struk {{ $transaksi->kode_transaksi }}
    </title>

    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">

</head>

<body class="struk-page">

<div class="struk">

    {{-- Informasi toko --}}
    <div class="struk-center">

        <h2 class="struk-title">
            BeysWear
        </h2>

        <p class="struk-text">
            Fashion Retail Store
        </p>

        <p class="struk-text">
            Jl. Jawa No. 1, Jember
        </p>

    </div>

    <div class="struk-line"></div>

    {{-- Informasi utama transaksi --}}
    <p class="struk-text">
        Kode: {{ $transaksi->kode_transaksi }}
    </p>

    <p class="struk-text">
        Tanggal:
        {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}
    </p>

    <p class="struk-text">
        Pembayaran:
        {{ $transaksi->pembayaran }}
    </p>

    <p class="struk-text">
        Status:
        {{ ucfirst($transaksi->status) }}
    </p>

    <div class="struk-line"></div>

    {{-- Daftar item yang dibeli dalam transaksi --}}
    @foreach($transaksi->details as $detail)

        <div class="struk-item">

            <div class="struk-item-name">
                {{ $detail->produk }}
            </div>

            <p class="struk-text">
                {{ $detail->ukuran ?? '-' }} /
                {{ $detail->warna ?? '-' }}
            </p>

            <div class="struk-row">

                <span>
                    {{ $detail->qty ?? $detail->jumlah }} x
                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                </span>

                <span>
                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                </span>

            </div>

        </div>

    @endforeach

    <div class="struk-line"></div>

    {{-- Ringkasan total pembayaran transaksi --}}
    <div class="struk-row struk-total">

        <span>Total</span>

        <span>
            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
        </span>

    </div>

    <div class="struk-line"></div>

    {{-- Pesan penutup pada struk --}}
    <div class="struk-center">

        <p class="struk-text">
            Terima kasih sudah berbelanja!
        </p>

        <p class="struk-text">
            Barang yang sudah dibeli tidak dapat dikembalikan.
        </p>

    </div>

</div>

{{-- Tombol cetak hanya muncul di halaman browser dan memanggil fitur print bawaan --}}
<button onclick="window.print()"
    class="btn btn-primer btn-print">
    Cetak Struk
</button>

</body>
</html>