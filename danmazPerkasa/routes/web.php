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
    return redirect('/Index');
});

Route::get('/Index', function (Controller $cont) {
    // session_start();
    // dd($_SESSION['user_id']);
    // dd(session('user_id'));
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
    return view('profile');
});

Route::get('/Profile/{wht}',[Controller::class,'Profile']);
Route::post('/Profile/{wht}-Update',[Controller::class,'ProfileUpdate']);



Route::get('/Logout',[AccountController::class, 'Logout']);

Route::get('/try', function(){
    return view('try');
});


Route::post('loginAccount',[AccountController::class, 'login']);
Route::post('RegistrationAccount',[AccountController::class, 'store']);

