<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('wassup');
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

Route::get('/kucing', function () {
    echo'welcome';
});


