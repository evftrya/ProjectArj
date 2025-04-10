<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
// require_once dirname(__FILE__) . '/pathofproject/Midtrans.php';


class TransaksiController extends Controller
{

    public function AcceptOrder($idTransaction){
        $transaction = Transaksi::where('id', $idTransaction)->get()->first();
        $transaction->Status_Pengiriman = 'Acceptted';
        // dd($transaction);
        $notif = new NotificationController();
        $notif->store(10,$transaction->id,$transaction->id_user);
        $transaction->save();
        
        // dd('Masuk');
        // return redirect('/Transaction/'.$idTransaction);
        return response()->json('Success');
        
    }
    
    public function RejectOrder($idTransaction){
        $transaction = Transaksi::where('id', $idTransaction)->get()->first();
        $transaction->Status_Pengiriman = 'Rejected';
        
        $notif = new NotificationController();
        $notif->store(11,$transaction->id,$transaction->id_user);
        $transaction->save();
        
        // return redirect('/Transaction/'.$idTransaction);
        // return response()->json('The transaction has been canceled due to exceeding the payment deadline');
        return response()->json('Success');
        // return response()->json('Reject');

    }

    public function GetSnapToken($Data){
        // dd($Data);
        $data = $Data[0];
        $Acc = new AccountController();
        $AccData = $Acc->getProfile($data->id_User);
        $DT = new DetailTransactionController();
        $DTData = $DT->getDetilTransactionsByIdTransaction($data->id); 
        $item_details = [];
        foreach ($Data as $item) {
            $item_details[] = [
                'id' => null,
                'price' => intval($item->price),
                'quantity' => intval($item->qty),
                'name' => substr($item->nama_product, 0, 50)
            ];
        }
        // dd( $data->weight/1000);
        $item_details[] = [
            'id' => null,
            'price' => intval($data->TotalShipping/intval(ceil($Data[0]->weight/1000))),
            'quantity' => intval(ceil($Data[0]->weight/1000)),
            'name' => 'Kg Shipping by '.$data->Shipping
        ];

        $item_details[] = [
            'id' => null,
            'price' => 2000,
            'quantity' => 1,
            'name' => 'Biaya Lain-lain'
        ];
        // dd($item_details);

        /*Install Midtrans PHP Library (https://github.com/Midtrans/midtrans-php)
        composer require midtrans/midtrans-php
                                    
        Alternatively, if you are not using **Composer**, you can download midtrans-php library 
        (https://github.com/Midtrans/midtrans-php/archive/master.zip), and then require 
        the file manually.   

        require_once dirname(__FILE__) . '/pathofproject/Midtrans.php'; */

        //SAMPLE REQUEST START HERE

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $data->id.env('CODE_TRANSACTION'),
                'gross_amount'=>$data->id+$data->TotalShopping+$data->TotalShipping,

            ),
            'customer_details' => array(
                'first_name' => $AccData->namaUser,
                'last_name' => '',
                'email' => 'arjun@gmail.com',
                'phone' => $AccData->Phone,
            ),
            'item_details' => $item_details
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        // dd($snapToken);
        
        return $snapToken;
        
    }
    public function store(Request $req,$wht){
        // dd($req);
        $Transaction = new Transaksi();
        $Transaction->id_user = session('user_id');
        $Transaction->save();
        $total = 0;
        // dd($Transaction);
        $contDT = new DetailTransactionController();
        if($wht=='Default'){
            $Checkout = $contDT->getAllData('Checkout');
        }
        else{
            $Checkout = $contDT->getAllData('TempCheckout');

        }
        // dd($Checkout);

        $contProd = new ProductsController();
        foreach($Checkout as $c){
            $contProd->CheckoutProduct($c->qty,$c->id_product);
            $contDT->UpdateStatus($c->id_product, 'WFP');
            $contDT->SetTransaction($c->id_Detail_transaction, $Transaction->id);
            $total+=(intval($c->price)*$c->qty);
        }
        $total+=2000;

        // dd($req->shippingCost."and".$total);
        $Transaction->TotalShopping = ($total + $req->shippingCost);
        $payment = $req->paymentMethod;
        if($req->bankMethod!=null){
            $payment = $req->paymentMethod." ".$req->bankMethod;
        }
        $Transaction->PaymentMethod = $payment;
        if($req->ntoes!=null){
            $Transaction->Notes = $req->ntoes;
        }
        $ship = (explode('|', $req->ship));
        $Transaction->Shipping = strtoupper($ship[0]." (".$ship[1].")");
        
        $Transaction->shippingEstimate = $req->shippingEstimate;
        $Transaction->Status_Pembayaran = "Waiting";
        $Transaction->Status_Pengiriman = "Waiting";
        $Transaction->TotalShipping = $req->shippingCost;
        $Transaction->save();
        // dd($Transaction);
        
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        
        $addr = new AddressController();
        $userData = $addr->getDataById();

        
        // ,'notif'=>$notifs
        return redirect('Transaction/'.$Transaction->id)->with('message','NoBack');
        
    }

    public function ManageTransaction(){
        
        $data = $this->getAll();
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        $TemplateRoute = '/ViewTransaction/';
        // ,'notif'=>$notifs
        // dd($Transaction);

        return view('User.Admin.ManageTransaction',['data'=>$data,'notif'=>$notifs,'TemplateRoute'=>$TemplateRoute]);
    }

    public function viewTransaction($idT){
        // dd($idT);
        $header = true;

        if($idT==0){
            return redirect('/');
        }
        // dd($idT);
        $Data = DB::table('transaksis as a')
        ->join('detail__transactions as b', 'b.Transaksis_id', '=', 'a.id')
        ->join('products as c', 'b.id_product', '=', 'c.id_product')
        ->join('photos as d', 'c.mainPhoto', '=', 'd.id_Photo')
        ->select(
            'a.*',
            DB::raw('a.created_at as Dibuat'),
            DB::raw('a.created_at as Deadline'),
            'b.*',
            'c.*',
            'd.*'
        )
        ->where('a.id', $idT)
        ->get();
    
        // dd($Data);
        
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // dd($Data[0]);
        $addr = new AddressController();
        $userData = $addr->getDataById();
        // dd($Data);
        // if($Data[0]->Status_Pembayaran!='Done'){
        //     $ST = $this->GetSnapToken($Data);
        // }
        // dd($Data);
        $Address = new AddressController();
        $detil = $Address->getDetil($Data[0]->id_user);

        // dd($Data);
            return view('User.Admin.ViewTransaction',['notif'=>$notifs,'data'=>$Data,'userData'=>$userData, 'idT'=>$idT,'Address'=>$detil,'header'=>$header]);
    }

    public function CancelTransaction($idTransaction){
        $idT = substr($idTransaction, 0, -1);
        $type = substr($idTransaction, -1);
        $transaction = Transaksi::where('id', $idT)->get()->first();
        $transaction->Status_Pembayaran = 'Cancel';
        $transaction->Status_Pengiriman = '-';
        $pesan = null;
        $notif = new NotificationController();
        if($transaction->save()){
            $pesan = 'The transaction was successfully cancelled';
            // dd($idT);
            $notif->store(4,$idT,session('user_id'));
            
        }
        else{
            $pesan = 'Sorry, Something Error';
        }
        
        if($type==1){
            
            return back()->with('message', $pesan);
        }
        else{
            return response()->json('The transaction has been canceled due to exceeding the payment deadline');
            // dd($idTransaction);
            $notif->store(3,$idT,session('user_id'));

        }
    }

    public function getAll(){
        $Transaction = DB::table('transaksis as a')
            ->select(
                'a.id',
                'a.created_at',
                'b.namaUser',
                'a.TotalShopping',
                'a.Shipping',
                'a.Notes',
            )
            ->join('users as b', 'a.id_user', '=', 'b.id_User')
            ->get();
            // dd($Transaction);
        return $Transaction;
    }

    public function toTransaction($idT){
        if($idT==0){
            return redirect('/');
        }
        // dd($idT);
        $Data = DB::table('transaksis as a')
        ->join('detail__transactions as b', 'b.Transaksis_id', '=', 'a.id')
        ->join('products as c', 'b.id_product', '=', 'c.id_product')
        ->join('photos as d', 'c.mainPhoto', '=', 'd.id_Photo')
        ->select(
            'a.*',
            DB::raw('a.created_at as Dibuat'),
            DB::raw('a.created_at as Deadline'),
            'b.*',
            'c.*',
            'd.*'
        )
        ->where('a.id', $idT)
        ->get();
    
        // dd($Data);
        
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // dd($Data[0]);
        $addr = new AddressController();
        $userData = $addr->getDataById();
        $ST=null;
        // dd($Data);
        if($Data[0]->Status_Pembayaran!='Done'){
            $ST = $this->GetSnapToken($Data);
        }

        // dd($Data);
        if(session('Role')=="Admin"){
            $Address = new AddressController();
            $detil = $Address->getDetil($Data[0]->id_user);
            return view('Transaction',['notif'=>$notifs,'data'=>$Data,'userData'=>$userData, 'idT'=>$idT,'Address'=>$detil]);
        }
        else{

            return view('Transaction',['notif'=>$notifs,'data'=>$Data,'userData'=>$userData, 'idT'=>$idT,'snapToken'=>$ST]);
        }

    }
    public function PaymentTransaction($snapToken){
        return view('User.Pelanggan.Payment',['snap'=>$snapToken]);
    }

    public function cekStatus($idTransaction){
        $data = Transaksi::where('id', $idTransaction)->get()->first();
        return response()->json($data->Status_Pembayaran);
    }

    public function RedirectNewestTransaction(){
        $transaction = Transaksi::where('id_user', session('user_id'))
            ->latest() // sama dengan orderBy('created_at', 'desc')
            ->first();
            
            $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth",
            ])->get("https://api.sandbox.midtrans.com/v2/".$transaction->id.env('CODE_TRANSACTION')."/status");
            // dd($response);
            $response = json_decode($response->body());
            // dd($response);
            if($response->status_code==200){
                $transaction->Status_Pembayaran = 'Done';
                $transaction->save();

                $notif = new NotificationController();
                //notif to Customer
                $notif->store(2,$transaction->id,session('user_id'));
                
                //notif to Admin
                $notif->store(7,$transaction->id,1);

                return redirect('/Transaction/'.$transaction->id)->with('notif','Pembayaran berhasil');
            }

    }   
        
}
