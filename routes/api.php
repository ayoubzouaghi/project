<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('add',   [UserController::class, 'addUser']);
Route::post('login', [UserController::class, 'Login']);

Route::group(['prefix' => 'users'], function () {
    Route::put('edit/{id}', [UserController::class, 'editUser'])->middleware('auth:api');;
    Route::delete('delete/{id}', [UserController::class, 'deleteUser'])->middleware('auth:api');
    Route::put('edit/user/{id}', [UserController::class, 'AdminUpdateUser'])->middleware('auth:api');
    Route::get('all', [UserController::class, 'index'])->middleware('auth:api');
    Route::put('update/profil/image', [UserController::class, 'UpdateProfilImage'])->middleware('auth:api');
    Route::get('user/profile/image', [UserController::class, 'GetProfilImage'])->middleware(('auth:api'));
    Route::post('user/register',   [UserController::class, 'UserRegister']);
});
Route::group(['prefix' => 'product'], function () {
    Route::post('add/product',   [ProductController::class, 'create'])->middleware('auth:api');
    Route::get('show/{id}',   [ProductController::class, 'show'])->middleware('auth:api');
    Route::get('user/product/{id}',   [ProductController::class, 'UserProduct'])->middleware('auth:api');
    Route::put('edit/{id}', [ProductController::class, 'update'])->middleware('auth:api');
});
