<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// login customer
Route::get('/depan', [App\Http\Controllers\KeranjangController::class, 'daftarmenu'])
     ->middleware(\App\Http\Middleware\CustomerMiddleware::class)
     ->name('depan');
Route::get('/login', function () {
    return view('login');
});

// tambahan route untuk proses login
use Illuminate\Http\Request;
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// untuk ubah password
Route::get('/ubahpassword', [App\Http\Controllers\AuthController::class, 'ubahpassword'])
    ->middleware('customer')
    ->name('ubahpassword');
Route::post('/prosesubahpassword', [App\Http\Controllers\AuthController::class, 'prosesubahpassword'])
    ->middleware('customer')
;
// prosesubahpassword
// tambah keranjang
Route::post('/tambah', [App\Http\Controllers\KeranjangController::class, 'tambahKeranjang'])->middleware(App\Http\middleware\CustomerMiddleware::class);
Route::get('/lihatkeranjang', [App\Http\Controllers\KeranjangController::class, 'lihatkeranjang'])->middleware(App\Http\middleware\CustomerMiddleware::class);
Route::delete('/hapus/{menu_id}', [App\Http\Controllers\KeranjangController::class, 'hapus'])->middleware(App\Http\middleware\CustomerMiddleware::class);
Route::get('/lihatriwayat', [App\Http\Controllers\KeranjangController::class, 'lihatriwayat'])->middleware(App\Http\middleware\CustomerMiddleware::class);
// untuk autorefresh
Route::get('/cek_status_pembayaran_pg', [App\Http\Controllers\KeranjangController::class, 'cek_status_pembayaran_pg']);
Route::get('/login', function () {
    return view('login');
});


Route::get('/hi', function () {
    echo'wassup';
});

Route::get('/hello', function () {
    dd(rupiah(15000));
});

Route::get('/ayam', function () {
    echo'welcome';
});


Route::get('/saya', function () {
    echo'welcome';
});



Route::get('/lebaran', function () {
    echo'welcome';
});

Route::get('/puasa', function () {
    echo'welcome';
});
Route::get('/kambing', function () {
    echo'welcome';
});
Route::get('/kelinci', function () {
    echo'welcome';
});


Route::get('/sapi', function () {
    echo'welcome';
});


Route::get('/panda', function () {
    echo'hallo';
});

Route::get('/yppie', function () {
    echo'arigathanks gozaimuch';
});

Route::get('/angsa', function () {
    echo'welcome';
});

Route::get('/test-view', function () {
    return view('filament.components.pembelianbahanbaku-table.blade', [
        'pembayaran' => App\Models\PembelianBahanBaku::all()
    ]);
});
// routes/web.php
Route::get('/test-view', function () {
    return view('filament.components.pembelianbahanbaku-table');
});

Route::model('karyawan', \App\Models\Karyawan::class);

use App\Http\Controllers\PengirimanEmailController;
Route::get('/proses_kirim_email_pembayaran', [PengirimanEmailController::class, 'proses_kirim_email_pembayaran']);

// untuk contoh pdf
use App\Http\Controllers\PDFController;
Route::get('/contohpdf', [PDFController::class, 'contohpdf']);

