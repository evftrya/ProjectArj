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


//LANDING PAGE
    Route::get('/', function (Controller $cont) {
        return redirect('/Index');
    });

    Route::get('/Index', function (Controller $cont) {
        // session_start();
        // dd($_SESSION['user_id']);
        // dd(session('user_id'));
        return view('landingpage');
    });


// ------------ SESSION -----------------
    //LOGIN
        Route::get('/Login', function (Controller $cont) {
            if($cont->AuthSystem()>0){
                // dd(session('direction'));
                if(session('direction')!=null){
                    return redirect(session('direction'));
                }
                else{
                    return redirect('/');
                }
            }
            else{
                return view('login');
            }
        });
        Route::post('loginAccount',[AccountController::class, 'login']);

    //REGISTER
        Route::get('/Register', function () {
            return view('register');
        });
        Route::post('RegistrationAccount',[AccountController::class, 'store']);

    //LOGOUT
     Route::get('/Logout',[AccountController::class, 'Logout']);
// -------------- END SESSION ------------------





// --------- PRODUCT ----------------
    Route::get('/Product',function(){
        return redirect('/Product/Info');
    });
    Route::get('/Product/{wht}',[Controller::class,'Product']);
    Route::get('/Profile/{wht}',[Controller::class,'Profile']);

    Route::get('/Detil-Product', function(){
        return view('/ProductDetil');
    });

    //CUSTOM
    Route::get('/Custom', function(){
        return view('Custom');
    });
// -------------- END PRODUCT -----------------


//PROFILE
    Route::get('/Profile', function (Controller $cont) {
        if($cont->AuthSystem()>0){
            return redirect('/Profile/Info');
        }
        $cont->authRoute('/Profile/Info');
    });
    Route::post('/Profile/{wht}-Update',[Controller::class,'ProfileUpdate']);

//CART
    Route::get('/Cart',function(){
        return view('Cart');
    });

    Route::post('Checkout', function(){
        return redirect ('/Checkout');
    });

//CHECKOUT
    Route::get('/Checkout', function(){
        return view('Checkout');
    });
