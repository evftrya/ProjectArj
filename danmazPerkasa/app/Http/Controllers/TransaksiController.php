<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\ProductsController;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function store(Request $req){
        $Transaction = new Transaksi();
        $Transaction->id_user = session('user_id');
        $Transaction->save();
        $total = 0;
        // dd($Transaction);
        $contDT = new DetailTransactionController();
        $Checkout = $contDT->getAllData('Checkout');
        // dd($Checkout);

        $contProd = new ProductsController();
        foreach($Checkout as $c){
            $contProd->CheckoutProduct($c->qty,$c->id_product);
            $contDT->UpdateStatus($c->id_product, 'donePayment');
            $contDT->SetTransaction($c->id_Detail_transaction, $Transaction->id);
            $total+=(intval($c->price)*$c->qty);
        }
        $total+=102000;

        $Transaction->Total = $total;
        $Transaction->save();

        return view('PaymentProses',['total'=>$total]);
        
    }
}
