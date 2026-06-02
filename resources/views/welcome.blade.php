@extends('layouts.app')

@section('content')

{{-- Hero section / halaman pembuka --}}
<div class="hero-fullscreen">

    <div class="hero-content-fix animate-bounce-in">

        <h2 style="font-size: 72px; color: #E0C58F; letter-spacing: 8px;">
            BEYSWEAR
        </h2>

        <p style="font-size: 1.4rem; color: #E0C58F; margin-bottom: 30px; font-weight: 300;">
            Katalog Fashion BeysWear
        </p>

        <div style="margin-top: 40px;">

            {{-- Scroll ke katalog produk --}}
            <a href="#katalog" class="btn btn-primer">
                Lihat Katalog
            </a>

            {{-- Akses login untuk admin dan kasir --}}
            <a href="{{ route('login') }}"
                class="login-karyawan-link">
                Login Karyawan
            </a>

        </div>

    </div>

</div>

{{-- Daftar produk yang ditampilkan untuk pengunjung --}}
<section class="catalog-section" id="katalog">

    <div class="catalog-header">

        <h2>Katalog Produk</h2>

        <p>
            Booking via WhatsApp, pembayaran dan pengambilan tetap di toko.
        </p>

    </div>

    <div class="catalog-grid">

        {{-- Data produk dikirim dari route landing page --}}
        @foreach($produk as $item)

            <div class="catalog-card">

                <div class="catalog-image">

                    {{-- Menampilkan foto produk jika tersedia --}}
                    @if($item->foto)

                        <img src="{{ asset('storage/' . $item->foto) }}"
                            alt="{{ $item->nama }}">

                    @else

                        <div class="catalog-no-image">
                            Tidak ada foto
                        </div>

                    @endif

                </div>

                <div class="catalog-content">

                    <span class="catalog-category">
                        {{ $item->kategori }}
                    </span>

                    <h3>{{ $item->nama }}</h3>

                    <h4>
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </h4>

                    {{-- Menampilkan daftar ukuran unik dari seluruh varian produk --}}
                    <p class="catalog-varian">

                        Ukuran:

                        {{ $item->varians->pluck('ukuran')->filter()->unique()->implode(', ') ?: '-' }}

                    </p>

                    {{-- Menampilkan daftar warna unik dari seluruh varian produk --}}
                    <p class="catalog-varian">

                        Warna:

                        {{ $item->varians->pluck('warna')->filter()->unique()->implode(', ') ?: '-' }}

                    </p>

                </div>

            </div>

        @endforeach

    </div>

</section>

{{-- Tombol WhatsApp mengambang untuk booking produk --}}
<a href="https://wa.link/ss6xd6"
    target="_blank"
    class="wa-floating">

    <span class="wa-tooltip">
        Ingin booking? Hubungi WhatsApp kami!
    </span>

    <img src="{{ asset('images/1E0A24E7-B551-4091-BCFE-2E5EBCC70670_4_5005_c.jpeg') }}"
        alt="WhatsApp"
        class="wa-logo">

    <span class="wa-badge"></span>

</a>

{{-- Footer landing page --}}
<footer class="landing-footer">

    <h3>BeysWear Fashion</h3>

    <p>
        Jl. Jawa No. 1, Jember
    </p>

</footer>

@endsection