<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth', 'verified'])
    ->get('/dashboard', fn () => view('dashboard'))
    ->name('dashboard');

// ============================
// PROFILE (SEMUA USER LOGIN)
// ============================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================
// ADMIN ONLY
// ============================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])
        ->name('dashboard.admin');

    // Produk
    Route::get('/produk/export', [ProductController::class, 'export'])->name('produk.export');
    Route::post('/produk/import', [ProductController::class, 'import'])->name('produk.import');
    Route::resource('produk', ProductController::class)->except(['show']);
  

    // Supplier
    Route::resource('supplier', SupplierController::class);

    // Pembelian
Route::prefix('pembelian')->group(function () {

    Route::get('/search', [PurchaseController::class, 'search']);
    Route::get('/{id}/print', [PurchaseController::class, 'print'])->name('pembelian.print');

    Route::get('/', [PurchaseController::class, 'index'])->name('pembelian.index');
    Route::get('/create', [PurchaseController::class, 'create'])->name('pembelian.create');
    Route::post('/', [PurchaseController::class, 'store'])->name('pembelian.store');
    Route::get('/{id}', [PurchaseController::class, 'show'])->name('pembelian.show');

});
    // Laporan Admin
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/penjualan', [ReportController::class, 'penjualan'])->name('penjualan');
        Route::get('/pembelian', [ReportController::class, 'pembelian'])->name('pembelian');
        Route::get('/stok', [ReportController::class, 'stok'])->name('stok');
        Route::get('/laba-rugi', [ReportController::class, 'labaRugi'])->name('laba_rugi');
    });
});

// ============================
// KASIR (KASIR + ADMIN BOLEH)
// ============================
Route::middleware(['auth', 'role:kasir,admin'])->group(function () {

    // Dashboard Kasir -> langsung transaksi
    Route::get('/dashboard/kasir', function () {
        return redirect()->route('kasir.transaksi');
    })->name('dashboard.kasir');

    // Penjualan Kasir
    Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/transaksi', [SalesController::class, 'create'])->name('transaksi');
        Route::post('/transaksi', [SalesController::class, 'store'])->name('transaksi.store');
        Route::get('/get-produk/{kode}', [SalesController::class, 'getProductByKode'])->name('getProduk');
        Route::get('/history', [SalesController::class, 'history'])->name('history');
        Route::get('/struk/{id}', [SalesController::class, 'struk'])->name('struk');
    });
});

// ============================
// OWNER ONLY (READ-ONLY)
// ============================
Route::middleware(['auth', 'role:owner'])->group(function () {

    // Dashboard Owner
    Route::get('/dashboard/owner', [OwnerController::class, 'dashboard'])
        ->name('dashboard.owner');

    // Menu Owner
    Route::get('/owner/laporan', [OwnerController::class, 'laporan'])
        ->name('owner.laporan');

    Route::get('/owner/laba-rugi', [OwnerController::class, 'labaRugi'])
        ->name('owner.laba_rugi');
});

require __DIR__ . '/auth.php';