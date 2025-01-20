<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TransaksiController;
use App\Models\Detail_Transaction;

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
    Route::get('/Ongkir',[AccountController::class,'Ongkir']);

    // Route::get('/Index', function (Controller $cont) {
    //     // session_start();
    //     // dd($_SESSION['user_id']);
    //     // dd(session('user_id'));
    //     return view('landingpage');
    // });
    Route::get('/Index', [ProductsController::class, 'LandingPage']);
    Route::get('/getCity/{idProvince}', [AddressController::class, 'getCity']);

    // Route::post('//Address/{$id}')



// ------------ SESSION -----------------
    //LOGIN
        Route::get('/Login', function (Controller $cont) {
            // dd(session('direction'));

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

     Route::post('cekLogin/{wht}',[AccountController::class, 'cekLogin']);
// -------------- END SESSION ------------------




// --------- PRODUCT ----------------
    Route::get('/Product',function(){
        return redirect('/Product/Info');
    });
    Route::get('/Product/{wht}',[ProductsController::class,'Product']);
    Route::get('/Profile/{wht}',[Controller::class,'Profile']);

    Route::get('/Detil-Product/{id}',[ProductsController::class,'DetilProducts']);
    Route::post('AddToCart/{idProduct}',[DetailTransactionController::class, 'store']);
    // Route::get('/Detil-Product', function(){
    //     return view('/ProductDetil');
    // });
    Route::post('/deleteProduct/{id}',[ProductsController::class, 'deleteProduct']);
    Route::post('/deletePart/{id}',[ProductsController::class, 'deletePart']);
    Route::post('/DeleteCart/{id}',[DetailTransactionController::class,'DeleteCart']);
    Route::post('/editProduct/{id}',[ProductsController::class,'updateProduct']);
    Route::post('/editPart/{id}',[ProductsController::class,'updatePart']);
    
    //CUSTOM
    Route::get('/Custom', function(){
        return view('Custom');
    });

    Route::get('/db', function(){
        return view('User.Admin.AdminDashboard');
    });
    
    //MANAGE-PRODUCT
    Route::get('/Manage/Product/{from}',[ProductsController::class, 'ProductManage']);
    Route::get('/getDataProduct/{idProduct}',[ProductsController::class, 'getAllDataProductById']);
    Route::get('/getDataPart/{idProduct}',[ProductsController::class, 'getAllDataPartById']);
    
    // -------------- END PRODUCT -----------------
    
    //--------------PART -------------------
    Route::get('/Part-Manage/{from}',[ProductsController::class, 'ProductManage']);
    
    Route::post('add-product/{wht}',[ProductsController::class,'store'])->name('add-product');
//--------------END PART -------------------


//PROFILE
    Route::get('/Profile', function (Controller $cont) {
        if($cont->AuthSystem()>0){
            return redirect('/Profile/Info');
        }
        $cont->authRoute('/Profile/Info');
    });
    Route::post('/Profile/{wht}-Update',[Controller::class,'ProfileUpdate']);

//CART
    Route::get('/Cart',[DetailTransactionController::class,'Cart']);

    // Route::post('Checkout', function(){
    //     return redirect ('/Checkout');
    // });
    Route::post('UpdateCart/{idproduct}/{idDT}',[DetailTransactionController::class,'UpdateCart']);

//CHECKOUT
    Route::get('/Checkout/{wht}/{qty}', [DetailTransactionController::class, 'CheckoutView']);

    Route::get('/tes', function(){
        return view('tes');
    });
    Route::get('/UpdateStatus/{idproduct}/{wht}',[DetailTransactionController::class, 'UpdateStatus']);

    // Route::get('/OrderDone',function(){
    //     return view('PaymentProses');
    // });

    Route::post('/OrderDone',[TransaksiController::class,'store']);

    Route::post('/Search/{wht}',[ProductsController::class,'search']);
    Route::post('/ProductEdit/{idProduct}',[ProductsController::class, 'update']);
    Route::get('/Manage/User', [AccountController::class, 'manageUser']);


//TRANSACTION
    Route::get('/Manage/Transaction', [TransaksiController::class, 'ManageTransaction']);


Route::get('/coba', [DetailTransactionController::class, 'getcek']);
Route::get('/midtrans', [TransaksiController::class, 'payment']);
Route::get('/OnContent/{idproduct}', [ProductsController::class, 'ContentOn']);
Route::get('/OffContent/{idproduct}', [ProductsController::class, 'ContentOff']);
Route::get('/isNew',[AddressController::class,'isNew']);