<footer>

    <div class="footer-grid">

        <div class="footer-col">
            <h4>BeysWear Fashion</h4>

            {{-- Informasi identitas toko yang ditampilkan di seluruh halaman setelah login --}}
            <p> Retail fashion. </p>

            <p style="margin-top:12px;">
                📍 Jl. Jawa No. 1, Jember<br>
                📞 0812-3456-7890<br>
                ✉ info@beyswear.com
            </p>
        </div>

        <div class="footer-col">

            {{-- Navigasi cepat menuju fitur utama sistem --}}
            <h4>Navigasi</h4>

            <ul>
                <li><a href="{{ route('dashboard') }}">Beranda</a></li>
                <li><a href="{{ route('penjualan') }}">Penjualan</a></li>
                <li><a href="{{ route('produk.index') }}">Manajemen Produk</a></li>
                <li><a href="{{ route('laporan') }}">Laporan & Statistik</a></li>
                <li><a href="{{ route('profil') }}">Pengaturan Akun</a></li>
            </ul>
        </div>

    </div>

    <p class="footer-bottom">
        &copy; 2026 <strong>BeysWear Fashion</strong>. Semua hak dilindungi.
    </p>

</footer>