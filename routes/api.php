<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::post('add',   [UserController::class, 'addUser']);
Route::post('login', [UserController::class, 'Login']);
Route::put('edit/{id}', [UserController::class, 'editUser'])->middleware('auth:api');;
