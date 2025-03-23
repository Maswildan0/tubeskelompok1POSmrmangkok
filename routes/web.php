<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hi', function () {
    echo'welcome';
});

Route::get('/hello', function () {
    echo'welcome';
});

Route::get('/ayam', function () {
    echo'welcome';
});

Route::get('/saya', function () {
    echo'welcome';
});


