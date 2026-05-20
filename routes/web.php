<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
-e 
Route::get('/dashboard', function () {
    return view('dashboard');
});
