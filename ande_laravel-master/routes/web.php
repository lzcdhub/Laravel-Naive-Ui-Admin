<?php

use App\Http\Controllers\AdminPro\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', [TestController::class, 'index']);
Route::get('admin', [TestController::class, 'admin']);
Route::get('opcache/{type}', [TestController::class, 'opcache']);
Route::get('phpinfo', [TestController::class, 'phpinfo']);

Route::get('workerman/{id}', [\App\Http\Controllers\AdminPro\WorkermanController::class, 'index']);
Route::get('workermans/seed_msg', [\App\Http\Controllers\AdminPro\WorkermanController::class, 'seedMsg']);
