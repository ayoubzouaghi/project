<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('add/user',   [UserController::class, 'addUser'])->middleware('auth:api');
Route::post('login', [UserController::class, 'Login']);
Route::get('all/products',   [ProductController::class, 'getAllProduct'])->middleware('auth:api');
Route::group(['prefix' => 'admin'], function () {
    Route::put('edit/user/{id}', [UserController::class, 'AdminUpdateUser'])->middleware('auth:api');
});

Route::group(['prefix' => 'users'], function () {
    Route::put('edit/{id}', [UserController::class, 'editUser'])->middleware('auth:api');;
    Route::delete('delete/{id}', [UserController::class, 'deleteUser'])->middleware('auth:api');
    Route::put('edit/user/{id}', [UserController::class, 'AdminUpdateUser'])->middleware('auth:api');
    Route::get('all', [UserController::class, 'index'])->middleware('auth:api');
    Route::get('details', [UserController::class, 'show'])->middleware('auth:api');
    Route::put('update/profil/image', [UserController::class, 'UpdateProfilImage'])->middleware('auth:api');
    Route::get('profil/image', [UserController::class, 'GetProfilImage'])->middleware(('auth:api'));
    Route::post('logout', [UserController::class, 'UserLogout'])->middleware(('auth:api'));
    Route::post('verif/token',  [UserController::class, 'VerifToken']);
    Route::put('update/password', [UserController::class, 'ChangePassword'])->middleware('auth:api');
    Route::post('user/register',   [UserController::class, 'UserRegister']);
});
Route::group(['prefix' => 'product'], function () {
    Route::put('update/image/{id}', [ProductController::class, 'UpdateProductImage'])->middleware('auth:api');
    Route::get('image/{id}', [ProductController::class, 'GetProductImage'])->middleware(('auth:api'));
    Route::post('add',   [ProductController::class, 'create'])->middleware('auth:api');
    Route::get('show/{id}',   [ProductController::class, 'show'])->middleware('auth:api');
    Route::get('user/product/{id}',   [ProductController::class, 'UserProduct'])->middleware('auth:api');
    Route::put('edit/{id}', [ProductController::class, 'update'])->middleware('auth:api');
    Route::delete('delete/{id}', [ProductController::class, 'deleteProduct'])->middleware('auth:api');
    Route::get('all', [ProductController::class, 'getAllProduct'])->middleware('auth:api');
});
