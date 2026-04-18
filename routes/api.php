<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini kamu bisa mendaftarkan rute API aplikasi kamu. Rute-rute ini
| otomatis dimasukkan ke dalam grup dengan prefix "api" dan middleware "api".
|
*/

Route::get('/test', function () {
    return response()->json(['message' => 'API routes aktif!']);
});
