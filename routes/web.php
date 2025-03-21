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

