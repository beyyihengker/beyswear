<?php

use App\Models\Produk;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukVarianController;
use App\Http\Controllers\PreferensiController;
use Illuminate\Support\Facades\Route;

// Landing page dibuat terbuka untuk customer.
// Data produk dibatasi hanya produk yang tersedia agar katalog publik
// tidak menampilkan produk yang sudah dinonaktifkan.
Route::get('/', function () {

    $produk = Produk::with('varians')
    ->where('tersedia', true)
    ->orderBy('created_at', 'desc')
    ->take(12)
    ->get();

    return view('welcome', ['produk' => $produk]);
});

// Semua route di dalam group ini hanya dapat diakses user yang sudah login
// dan sudah lolos middleware verified.
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menghapus data session kunjungan dashboard tanpa logout user.
    Route::post('/reset-session', function () {session()->forget(['visit_count','first_visit','last_visit']);return back();})->name('reset.session');

    // Route profil user aktif.
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::patch('/profil', [ProfilController::class, 'update'])->name('profile.update');
    Route::patch('/profil/password', [ProfilController::class, 'updatePassword'])->name('profile.password');

    // Preferensi tampilan disimpan melalui cookie dan dipanggil lewat fetch/AJAX.
    Route::post('/preferensi/save', [PreferensiController::class, 'save'])->name('preferensi.save');
    Route::get('/preferensi/get',[PreferensiController::class, 'getPreference'])->name('preferensi.get');

    // Menambahkan varian produk dari halaman detail produk.
    Route::post('/varian',[ProdukVarianController::class, 'store'])->name('varian.store');

    // Route yang dapat diakses admin dan kasir.
    // Digunakan untuk aktivitas operasional toko.
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
        Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
        Route::get('/penjualan/{transaksi}/struk', [PenjualanController::class, 'struk'])->name('penjualan.struk');
        Route::patch('/penjualan/{transaksi}/cancel', [PenjualanController::class, 'cancel'])->name('penjualan.cancel');

        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');

        // Endpoint JSON untuk fitur live search produk.
        Route::get('/search-produk', [ProdukController::class, 'search']);

        // Trash produk digunakan untuk soft delete dan restore.
        Route::get('/produk-trash', [ProdukController::class, 'trash'])->name('produk.trash');
        Route::patch('/produk/{id}/restore', [ProdukController::class, 'restore'])->name('produk.restore');
    });

    // Route khusus admin.
    // Admin memiliki akses untuk mengubah master data dan melihat laporan.
    Route::middleware('role:admin')->group(function () {
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Detail produk dapat diakses admin dan kasir.
    // Route ini diletakkan setelah route create/edit agar tidak bentrok
    // dengan parameter {produk}.
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
    });
});

require __DIR__.'/auth.php';