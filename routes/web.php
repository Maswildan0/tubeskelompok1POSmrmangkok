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

Route::model('karyawan', \App\Models\Karyawan::class);

use App\Http\Controllers\PengirimanEmailController;
Route::get('/proses_kirim_email_pembayaran', [PengirimanEmailController::class, 'proses_kirim_email_pembayaran']);

