<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
// require_once dirname(__FILE__) . '/pathofproject/Midtrans.php';


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
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs

        return view('User.Pelanggan.PaymentProses',['total'=>$total,'notif'=>$notifs]);
        
    }

    public function ManageTransaction(){
        
        $data = $this->getAll();
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($Transaction);

        return view('User.Admin.ManageTransaction',['data'=>$data,'notif'=>$notifs]);
    }

    public function getAll(){
        $Transaction = DB::table('transaksis as a')
            ->select(
                'a.id',
                'a.created_at',
                'b.namaUser',
                'a.Total',
                'a.Shipping',
                'a.Notes',
            )
            ->join('users as b', 'a.id_user', '=', 'b.id_User')
            ->get();
        return $Transaction;
    }

    public function payment(){
        /*Install Midtrans PHP Library (https://github.com/Midtrans/midtrans-php)
        composer require midtrans/midtrans-php
                                    
        Alternatively, if you are not using **Composer**, you can download midtrans-php library 
        (https://github.com/Midtrans/midtrans-php/archive/master.zip), and then require 
        the file manually.   

        require_once dirname(__FILE__) . '/pathofproject/Midtrans.php'; */

        //SAMPLE REQUEST START HERE

        // Set your Merchant Server Key
        // \Midtrans\Config::$serverKey = 'SB-Mid-server-DF9qJOSdrGs6DB01TgtG5AX1';
        // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        // \Midtrans\Config::$isProduction = true;
        // // Set sanitization on (default)
        // \Midtrans\Config::$isSanitized = true;
        // // Set 3DS transaction for credit card to true
        // \Midtrans\Config::$is3ds = true;

        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => rand(),
        //         'gross_amount' => 105,
        //     ),
        //     'customer_details' => array(
        //         'first_name' => 'budi',
        //         'last_name' => 'pratama',
        //         'email' => 'budi.pra@example.com',
        //         'phone' => '08111222333',
        //     ),
        // );

        // $snapToken = \Midtrans\Snap::getSnapToken($params);
        // // return $snapToken;
        // // dd($snapToken);

        // return view('coba',['snapToken'=>$snapToken]);


        
    }
}
