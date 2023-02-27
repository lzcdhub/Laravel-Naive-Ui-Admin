<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('/news/info',\Modules\News\Http\Controllers\NewsInfoController::class);
Route::resource('/news/category', \Modules\News\Http\Controllers\CategoryController::class);
Route::resource('/news/project', \Modules\News\Http\Controllers\NewsProjectController::class);

Route::get('/news/state/{id}',[\Modules\News\Http\Controllers\NewsInfoController::class,'state']);
Route::get('/news/project_state/{id}',[\Modules\News\Http\Controllers\NewsProjectController::class,'state']);
Route::get('/news/category_select',[\Modules\News\Http\Controllers\CategoryController::class,'categorySelect']);

