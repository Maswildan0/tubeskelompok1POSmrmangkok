<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

