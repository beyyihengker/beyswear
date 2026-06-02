<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'BeysWear Fashion')</title>

    <script>

        // Menerapkan dark mode sebelum CSS utama dimuat.
        // Ini mencegah tampilan sempat flash ke light mode saat halaman dibuka.
        if(
            document.cookie.includes(
                'theme=dark'
            )
        ){

            document.documentElement
                .classList.add('dark');
        }

    </script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Tempat halaman tertentu menambahkan CSS tambahan --}}
    @stack('styles')
</head>

<body class="{{ Auth::check() ? 'dashboard-body' : 'landing-page-body' }}">

    {{-- Navbar hanya ditampilkan jika user sudah login --}}
    @auth
        @include('partials.navbar')
    @endauth

    {{-- Flash message untuk notifikasi sukses --}}
    @if (session('success'))
        <div id="flash-message" class="alert alert-success fixed-popup animate-bounce-in" role="alert">
            <div style="display: flex; align-items: center;">
                <i data-lucide="check-circle" style="width: 18px; margin-right: 10px;"></i>
                {{ session('success') }}
            </div>

            <button type="button" onclick="document.getElementById('flash-message').remove()">
                <i data-lucide="x" style="width: 16px;"></i>
            </button>
        </div>
    @endif

    {{-- Flash message untuk notifikasi error --}}
    @if (session('error'))
        <div id="flash-message" class="alert alert-error fixed-popup animate-bounce-in" role="alert">
            <div style="display: flex; align-items: center;">
                <i data-lucide="alert-circle" style="width: 18px; margin-right: 10px;"></i>
                {{ session('error') }}
            </div>

            <button type="button" onclick="document.getElementById('flash-message').remove()">
                <i data-lucide="x" style="width: 16px;"></i>
            </button>
        </div>
    @endif

    {{-- Jika user login, konten memakai page-container.
        Jika guest, landing page dibiarkan full layout. --}}
    <main class="{{ Auth::check() ? 'page-container' : '' }}">
        @yield('content')
    </main>

    {{-- Footer hanya ditampilkan pada halaman setelah login --}}
    @auth
        @include('partials.footer')
    @endauth

   {{-- JS utama aplikasi --}}
   <script src="{{ asset('js/script.js') }}"></script>

   {{-- Library icon Lucide --}}
   <script src="https://unpkg.com/lucide@latest"></script>

   <script>
       document.addEventListener('DOMContentLoaded', function () {

           // Mengubah elemen <i data-lucide=""> menjadi ikon SVG.
           lucide.createIcons();
       });

       // Menghilangkan flash message otomatis setelah 5 detik.
       setTimeout(() => {
           const flash = document.getElementById('flash-message');

           if (flash) {
               flash.style.transition = "all 0.6s ease";
               flash.style.opacity = "0";
               flash.style.transform = "translateX(50px)";
               setTimeout(() => flash.remove(), 600);
           }
       }, 5000);
   </script>

   {{-- Tempat halaman tertentu menambahkan JavaScript tambahan --}}
   @stack('scripts')

</body>
</html>