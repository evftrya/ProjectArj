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
use App\Models\Transaksi;
use Illuminate\Notifications\Notification;

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
Route::get('/Index', [ProductsController::class, 'LandingPage']);
Route::get('/PaymentStatus/{id}', [TransaksiController::class, 'cekStatus']);
Route::get('/getCity/{idProvince}', [AddressController::class, 'getCity']);

// ------------ SESSION -----------------
//LOGIN
Route::get('/login', function(){
    return redirect('/Login');
});
Route::get('/Login', function (Controller $cont) {

    if($cont->AuthSystem()>0){
        // dd(session('direction'));
        if(session('direction')!=null){
            $save = session('direction');
            session(['direction' => null]);

            return redirect($save);
        }
        else{
            return redirect('/');
        }
    }
    else{
        return view('login');
    }
});
// Route::match(['get', 'post'], 'loginAccount', [AccountController::class, 'login']);
Route::post('loginAccount', [AccountController::class, 'login']);

//REGISTER
Route::get('/Register', function () {
    return view('register');
});
Route::match(['get', 'post'], 'RegistrationAccount', [AccountController::class, 'store'])->middleware('CheckFormRequest');

//LOGOUT
Route::get('/Logout', [AccountController::class, 'Logout']);

// Route::match(['get', 'post'], 'cekLogin/{wht}', [AccountController::class, 'cekLogin'])->middleware('CheckFormRequest');
Route::match(['get', 'post'], 'cekLogin/{wht}', [AccountController::class, 'cekLogin'])->middleware('CheckFormRequest');
Route::match(['get', 'post'], '/Search/{wht}', [ProductsController::class,'search'])->middleware('CheckFormRequest');

// -------------- END SESSION ------------------


//With Login
//Public
Route::get('/History', [TransaksiController::class, 'historyCustomer'])->middleware('role:User');
Route::match(['get', 'post'], '/OrderDone/{wht}', [TransaksiController::class, 'store'])->middleware('CheckFormRequest');
Route::match(['get', 'post'], '/OrderDoneCustom/{dataPart}', [TransaksiController::class, 'CustomTransaction'])->middleware('CheckFormRequest');
Route::match(['get', 'post'], '/AddToCart/{idProduct}', [DetailTransactionController::class, 'store'])->middleware('CheckFormRequest');

Route::match(['get', 'post'], '/DeleteCart/{id}', [DetailTransactionController::class, 'DeleteCart'])->middleware('CheckFormRequest');

//CART-CHECKOUT-TRANSACTION
//CART
Route::get('/Cart', [DetailTransactionController::class, 'Cart'])->middleware('role:User');
Route::get('/deleteTempCheckout', [DetailTransactionController::class, 'deleteTempCheckout']);

Route::match(['get', 'post'], 'UpdateCart/{idproduct}/{idDT}', [DetailTransactionController::class, 'UpdateCart'])->middleware('CheckFormRequest');

//CHECKOUT, DLL
Route::get('/Checkout/{wht}/{qty}', [DetailTransactionController::class, 'CheckoutView'])->middleware('role:User');
Route::get('/Checkout-view-direct/{wht}', [DetailTransactionController::class, 'CheckoutViewDirect'])->middleware('role:User');
Route::match(['get', 'post'], 'CheckoutCustom', [DetailTransactionController::class, 'CheckoutViewCustom'])->middleware('CheckFormRequest');
Route::get('/Transaction/{idTransaction}', [TransaksiController::class, 'toTransaction'])->middleware('role:Admin|User');
Route::post('/ReadNotif', [NotificationController::class, 'Read1Notif'])->middleware('role:Admin|User');
//
Route::get('/UpdateStatus/{idproduct}/{wht}', [DetailTransactionController::class, 'UpdateStatus'])->middleware('role:User');
// Route::get('/Transaction', function (Controller $cont){

//     if($cont->AuthSystem()>0){
//         if(session('direction')!=null){
//             return redirect(session('direction'));
//         }
//         else{
//             return view('Transaction');
//         }
//     }
//     else{
//         session(['direction' => '/Transaction']);
//         return redirect('/Login');
//     }
// });

//TRANSACTION
Route::get('/Transaction/Cancel/{idTransaction}', [TransaksiController::class, 'CancelTransaction'])->middleware('role:User');
Route::get('/Manage/Transaction', [TransaksiController::class, 'ManageTransaction'])->middleware('role:Admin');
Route::get('/ViewTransaction/{idT}', [TransaksiController::class, 'viewTransaction'])->middleware('role:Admin');


//PAYMENT
// Route::get('/Payment/{snapToken}', [TransaksiController::class, 'PaymentTransaction']);
Route::get('/RedirectNewestTransaction', [TransaksiController::class, 'RedirectNewestTransaction'])->middleware('role:User');

//ADMIN
Route::get('/Transaction/AcceptOrder/{idTransaction}', [TransaksiController::class, 'AcceptOrder'])->middleware('role:Admin');
Route::get('/Transaction/RejectOrder/{idTransaction}', [TransaksiController::class, 'RejectOrder'])->middleware('role:Admin');

//Admin
Route::get('/Manage/User', [AccountController::class, 'manageUser'])->middleware('role:Admin');


Route::get('/viewUser/{id}', [AccountController::class, 'viewController'])->middleware('role:Admin');
Route::get('/DeactiveAccount/{idAccount}', [AccountController::class, 'Deactive'])->middleware('role:Admin');
Route::get('/DeleteAccount/{idAccount}', [AccountController::class, 'DeleteAccount'])->middleware('role:Admin');
Route::get('/Manage/Product/{from}', [ProductsController::class, 'ProductManage'])->middleware('role:Admin');

//--------------PRODUCT -------------------
Route::get('/viewProduct/{id}', [ProductsController::class, 'ViewProductAdmin'])->middleware('role:Admin');
Route::get('/getDataProduct/{idProduct}', [ProductsController::class, 'getAllDataProductById'])->middleware('role:Admin|User');
Route::match(['get', 'post'], '/editProduct/{id}', [ProductsController::class, 'updateProduct'])->middleware('CheckFormRequest')->middleware('role:Admin');
Route::get('/deleteProduct/{id}', [ProductsController::class, 'deleteProduct'])->middleware('role:Admin');

//--------------PART -------------------
Route::get('/Part-Manage/{from}', [ProductsController::class, 'ProductManage'])->middleware('role:Admin');
Route::match(['get', 'post'], 'add-product/{wht}', [ProductsController::class, 'store'])->name('add-product')->middleware('CheckFormRequest')->middleware('role:Admin');
Route::match(['get', 'post'], '/editPart/{id}', [ProductsController::class, 'updatePart'])->middleware('CheckFormRequest')->middleware('role:Admin');
Route::get('/deletePart/{id}', [ProductsController::class, 'deletePart'])->middleware('role:Admin');
Route::get('/getDataPart/{idProduct}', [ProductsController::class, 'getAllDataPartById'])->middleware('role:Admin|User');

//AllRole
//PROFILE
Route::get('/Profile', function (Controller $cont) {
    if($cont->AuthSystem()>0){
        return redirect('/Profile/Info');
    }
    $cont->authRoute('/Profile/Info');
})->middleware('role:Admin|User');
Route::match(['get', 'post'], '/Profile/{wht}-Update', [Controller::class, 'ProfileUpdate'])->middleware('CheckFormRequest');

//END WITH LOGIN


// --------------------------------------------------------------------------------------------------------------------


Route::get('/OnContent/{idproduct}', [ProductsController::class, 'ContentOn'])->middleware('role:Admin');
Route::get('/OffContent/{idproduct}', [ProductsController::class, 'ContentOff'])->middleware('role:Admin');
//NOW
Route::get('/isNew/{idProduct}', [AddressController::class, 'isNew']);

Route::get('/Product', function(){
    return redirect('/Product/AllProduct');
});
Route::get('/Product/{wht}', [ProductsController::class, 'Product']);
Route::get('/Profile/{wht}', [Controller::class, 'Profile']);

Route::get('/Detil-Product/{id}', [ProductsController::class, 'DetilProducts']);
Route::get('/Custom', [ProductsController::class, 'Custom'])->middleware('role:User');
Route::get('/PageNotFound', function(){
    return view('OutOfPages');
})->name('page.notfound');
Route::get('/testPage', function(){
    return view('testPage');
});

Route::get('/tes', function(){
    return view('tes');
});
Route::post('/RedAllNotif', [NotificationController::class,'RedAllNotif']);
// Route::get('/testBayar', function(){
//     if(session('user_id')>0){
//         return view('User.Admin.viewProfile');
//     }
//     else{
//         return view('OutOfPages');
//     }
// });
