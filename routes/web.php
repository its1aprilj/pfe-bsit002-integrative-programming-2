<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APRIL;

Route::get('/', function () {
    return "Welcome to the Student Information Page!";
});

Route::get('/APRIL', [APRIL::class, 'index']);