<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\NotificationController;
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

    Route::get('/Bayar',[TransaksiController::class,'Bayar']);
    Route::post('/payment',[TransaksiController::class,'payment']);
    Route::get('/Index', [ProductsController::class, 'LandingPage']);
    Route::get('/PaymentStatus/{id}', [TransaksiController::class, 'cekStatus']);
    Route::get('/getCity/{idProvince}', [AddressController::class, 'getCity']);

    // Route::post('//Address/{$id}')



// ------------ SESSION -----------------
    //LOGIN
        Route::get('/login',function(){
            return redirect('/Login');
        });
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
                // dd(session());
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


//With Login
    //Public
        Route::post('/Search/{wht}',[ProductsController::class,'search']);
        Route::post('/OrderDone/{wht}',[TransaksiController::class,'store']);
        Route::post('AddToCart/{idProduct}',[DetailTransactionController::class, 'store']);
        Route::post('/DeleteCart/{id}',[DetailTransactionController::class,'DeleteCart']);

        //CART-CHECKOUT-TRANSACTION
            //CART
                Route::get('/Cart',[DetailTransactionController::class,'Cart']);
                Route::get('/deleteTempCheckout',[DetailTransactionController::class,'deleteTempCheckout']);
            
                Route::post('UpdateCart/{idproduct}/{idDT}',[DetailTransactionController::class,'UpdateCart']);
            
            //CHECKOUT, DLL
                Route::get('/Checkout/{wht}/{qty}', [DetailTransactionController::class, 'CheckoutView']);
                Route::get('/Checkout-view-direct/{wht}',[DetailTransactionController::class,'CheckoutViewDirect']);
                Route::get('Transaction/{idTransaction}',[TransaksiController::class,'toTransaction']);
                //
                Route::get('/UpdateStatus/{idproduct}/{wht}',[DetailTransactionController::class, 'UpdateStatus']);
                Route::get('Transaction', function(Controller $cont){

                    if($cont->AuthSystem()>0){
                        // dd(session('direction'));
                        if(session('direction')!=null){
                            return redirect(session('direction'));
                        }
                        else{
                            return view('Transaction');
                        }
                    }
                    else{
                        session(['direction' => '/Transaction']);
                        return redirect('/Login');
                    }
                });
                Route::get('/Manage/Transaction', [TransaksiController::class, 'ManageTransaction']);
                Route::get('/OrderDone',function(){
                    return view('PaymentProses');
                });

    
    
            
            
    //Admin
        
        Route::get('/Manage/User', [AccountController::class, 'manageUser']);
        Route::get('/db', function(){
            return view('User.Admin.AdminDashboard');
        });
        
        Route::get('/Manage/Product/{from}',[ProductsController::class, 'ProductManage']);
        
        //--------------PRODUCT -------------------
            Route::get('/getDataProduct/{idProduct}',[ProductsController::class, 'getAllDataProductById']);
            // Route::post('/ProductEdit/{idProduct}',[ProductsController::class, 'update']);
            Route::post('/editProduct/{id}',[ProductsController::class,'updateProduct']);
            Route::post('/deleteProduct/{id}',[ProductsController::class, 'deleteProduct']);
        
        //--------------PART -------------------
            Route::get('/Part-Manage/{from}',[ProductsController::class, 'ProductManage']);
            Route::post('add-product/{wht}',[ProductsController::class,'store'])->name('add-product');
            Route::post('/editPart/{id}',[ProductsController::class,'updatePart']);
            Route::post('/deletePart/{id}',[ProductsController::class, 'deletePart']);
            Route::get('/getDataPart/{idProduct}',[ProductsController::class, 'getAllDataPartById']);
    

    //AllRole
        //PROFILE
            Route::get('/Profile', function (Controller $cont) {
                if($cont->AuthSystem()>0){
                    return redirect('/Profile/Info');
                }
                $cont->authRoute('/Profile/Info');
            });
            Route::post('/Profile/{wht}-Update',[Controller::class,'ProfileUpdate']);


            
//END WITH LOGIN






// --------------------------------------------------------------------------------------------------------------------


    
        

    Route::get('/coba', [DetailTransactionController::class, 'getcek']);
    Route::get('/midtrans', [TransaksiController::class, 'payment']);
    Route::get('/OnContent/{idproduct}', [ProductsController::class, 'ContentOn']);
    Route::get('/OffContent/{idproduct}', [ProductsController::class, 'ContentOff']);
    Route::get('/isNew',[AddressController::class,'isNew']);




    Route::get('/Product',function(){
        return redirect('/Product/Info');
    });
    Route::get('/Product/{wht}',[ProductsController::class,'Product']);
    Route::get('/Profile/{wht}',[Controller::class,'Profile']);
    
    Route::get('/Detil-Product/{id}',[ProductsController::class,'DetilProducts']);
    // Route::get('/Detil-Product', function(){
    //     return view('/ProductDetil');
    // });
    
    
    Route::get('/Custom', function(){
        return view('User.Pelanggan.Custom');
    });

Route::get('/tes', function(){
    return view('tes');
});



