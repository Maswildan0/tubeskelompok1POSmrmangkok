<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hi', function () {
    echo'welcome';
});

Route::get('/hello', function () {
    echo'halooo';
});

Route::get('/ayam', function () {
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

Route::get('/angsa', function () {
    echo'welcome';
});

Route::model('karyawan', \App\Models\Karyawan::class);
