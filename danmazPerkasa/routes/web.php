<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Controller;

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
$controller = new Controller();
Route::get('/', function (Controller $cont) {
    return view('landingpage');
});

Route::get('/Login', function (Controller $cont) {
    if($cont->AuthSystem()>0){
        return redirect('/');
    }
    else{
        return view('login');
    }
});

Route::get('/Register', function () {
    return view('register');
});

Route::get('/Profile', function () {
    return view('trash/Profile');
});

Route::get('/try', function(){
    return view('try');
});


Route::post('loginAccount',[AccountController::class, 'login']);
Route::post('RegistrationAccount',[AccountController::class, 'store']);

