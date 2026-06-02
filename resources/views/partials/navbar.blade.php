<nav>
    <div class="nav-inner">

        {{-- Identitas dan branding aplikasi --}}
        <div class="nav-brand">
            <img src="{{ asset('images/IMG_7126.PNG') }}">
            <div class="brand-text">
                <h1>BeysWear Fashion</h1>
                <p>Sistem Manajemen Retail</p>
            </div>
        </div>

        @auth

            {{-- Menu navigasi hanya ditampilkan untuk user yang sudah login --}}
            <ul class="nav-menu">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'aktif' : '' }}">Beranda</a></li>
                <li><a href="{{ route('penjualan') }}" class="{{ request()->routeIs('penjualan') ? 'aktif' : '' }}">Penjualan</a></li>
                <li><a href="{{ route('produk.index') }}" class="{{ request()->routeIs('produk.*') ? 'aktif' : '' }}">Produk</a></li>

                {{-- Menu laporan dibatasi hanya untuk role admin --}}
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'aktif' : '' }}">Laporan</a></li>
                @endif

                {{-- Dropdown profil, preferensi tampilan, dan logout --}}
                <li class="nav-item-dropdown" id="navDropdown">
                    <div class="avatar-circle nav-dropdown-toggle">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="dropdown-content nav-dropdown-menu">
                        <div class="nav-dropdown-header">
                            <p class="user-name">Halo, {{ Auth::user()->name }}!</p>
                            <p class="user-email" style="font-size: 10px; color: #888;">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="nav-dropdown-divider"></div>

                        <a href="{{ route('profil') }}" class="nav-dropdown-item">Profil Saya</a>

                        {{-- Manajemen user hanya tersedia untuk admin --}}
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('users.index') }}" class="nav-dropdown-item">Manajemen User</a>
                        @endif

                        {{-- Pengaturan tema dan ukuran font disimpan melalui cookie dan endpoint preferensi --}}
                        <div class="nav-pref-wrap">

                            <button id="darkToggle" type="button" class="theme-switch">
                                <span class="theme-icon">☀</span>
                            </button>

                            <button id="fontToggle" type="button" class="pref-btn">
                                Aa
                            </button>

                        </div>

                        <div class="nav-dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-dropdown-item logout-btn" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; font-family: inherit;">
                                Logout
                            </button>
                        </form>

                    </div>
                </li>
            </ul>

            {{-- Tombol menu untuk tampilan mobile --}}
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>

        @endauth
    </div>
</nav>