<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
| resource: index create store show edit update destroy
*/

//用户管理
Route::resource('admin', UserController::class);//后台用户
Route::resource('member', MemberController::class);//前台用户
Route::get('/menus', [App\Http\Controllers\MenuController::class, 'menus']); //用户菜单列表
Route::post('/login', [UserController::class, 'login'])->withoutMiddleware('auth:sanctum')->name('user_login');
Route::post('/logout', [UserController::class, 'logOut'])->name('user_logout');

//后台用户角色
Route::resource('role', App\Http\Controllers\RoleController::class); //角色路由
Route::post('/assign_role', [App\Http\Controllers\RoleController::class, 'assignRole']); //给用户分配角色

//权限与角色
Route::get('/permission_tree', [App\Http\Controllers\PermissionsController::class, 'permissionsTree']); //权限树列表
Route::post('/add_permission', [App\Http\Controllers\PermissionsController::class, 'addPermission']); //添加权限&添加菜单
Route::post('/edit_permission', [App\Http\Controllers\PermissionsController::class, 'editPermission']); //编辑权限
Route::post('/assign_permission', [App\Http\Controllers\PermissionsController::class, 'assignPermission']); //给角色分配权限

//文件管理
Route::resource('media_file', App\Http\Controllers\MediaFileController::class);

//其他接口
Route::any('/area/area_list', [App\Http\Controllers\AreaController::class, 'index']);//地区列表
Route::any('/admin/upload', [UserController::class, 'uploadFile']);//上传文件
Route::any('/test', [\App\Http\Controllers\AdminPro\TestController::class, 'index'])->withoutMiddleware('auth:sanctum');//测试接口


//路由权限
Route::group(['middleware' => ['role:super-admin']], function () {
    //
});

Route::group(['middleware' => ['permission:publish articles']], function () {
    //
});

Route::group(['middleware' => ['role:super-admin', 'permission:publish articles']], function () {
    //
});

Route::group(['middleware' => ['role_or_permission:publish articles']], function () {
    //
});

