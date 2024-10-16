<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing');
});

Route::get('/Login', function () {
    return view('LoginPage');
});

Route::get('/Register', function () {
    return view('RegisterPage');
});

Route::get('/Profile', function () {
    return view('Profile');
});

Route::get('/try', function(){
    return view('try');
});
