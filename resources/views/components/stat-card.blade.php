@props(['judul', 'nilai', 'warna' => '#112250'])

<div class="sb-card">
    <p class="sb-title">{{ $judul }}</p>
    <p class="sb-angka" style="color: {{ $warna }}">{{ $nilai }}</p>
</div>