<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'index'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login');

Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
