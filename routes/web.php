<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', 'Auth\LoginController@login');

Route::get('/get_cabinets/{user_id}', 'Common\DesktopController@getCabinets');

Route::get('/login_test', function() {
    return 'login test';
});