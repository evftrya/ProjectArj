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
use Illuminate\Support\Str;
// require_once dirname(__FILE__) . '/pathofproject/Midtrans.php';


class TransaksiController extends Controller
{

    public function AcceptOrder($idTransaction){
        $transaction = Transaksi::where('id', $idTransaction)->get()->first();
        $transaction->Status_Transaksi = 'Acceptted';
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
        $transaction->Status_Transaksi = 'Rejected';
        
        $notif = new NotificationController();
        //kembalikan stok
        (new ProductsController())->TransactionUpdate($idTransaction,'plus');
        //notifikasi
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
        if($wht=='Default'||$wht=='Custom'){
            $Checkout = $contDT->getAllData('Checkout');
            // dd($Checkout);

            if($wht=='Custom'){
                foreach($Checkout as $dt){
                    (new ProductsController())->UpdateStokMinus($dt->id_product,$dt->qty);

                }
            }
        }
        else{
            $Checkout = $contDT->getAllData('TempCheckout');

        }

        if($wht=='Custom'){
            $Transaction->type_transaction = 'Custom';
        }
        else{
            $Transaction->type_transaction = 'Product';

        }
        // dd($Checkout);

        $contProd = new ProductsController();
        foreach($Checkout as $c){
            // $contProd->CheckoutProduct($c->qty,$c->id_product);
            $contDT->UpdateStatus($c->id_product, 'WFP');
            $contDT->SetTransaction($c->id_Detail_transaction, $Transaction->id);
            $total+=(intval($c->price)*$c->qty);
        }
        $total+=2000;

        // dd($req->shippingCost."and".$total);
        $Transaction->TotalShopping = ($total + $req->shippingCost);
        // $payment = $req->paymentMethod;
        if($req->bankMethod!=null){
            $payment = $req->paymentMethod." ".$req->bankMethod;
        }
        $Transaction->PaymentMethod = null;
        if($req->ntoes!=null){
            $Transaction->Notes = $req->ntoes;
        }
        $ship = (explode('|', $req->ship));
        $Transaction->Shipping = strtoupper($ship[0]." (".$ship[1].")");
        
        $Transaction->shippingEstimate = $req->shippingEstimate;
        $Transaction->Status_Pembayaran = "Waiting";
        $Transaction->Status_Pengiriman = "Waiting";
        $Transaction->Status_Transaksi = "Waiting";
        $Transaction->TotalShipping = $req->shippingCost;

        $Address = new AddressController();
        $detil = $Address->getDetil(session('user_id'));
        $Transaction->Address = $detil;
        
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
        // dd($Data);
        $detil = $Address->getDetil($Data[0]->id_user);

        // dd($Data);
            return view('User.Admin.ViewTransaction',['notif'=>$notifs,'data'=>$Data,'userData'=>$userData, 'idT'=>$idT,'Address'=>$detil,'header'=>$header]);
    }

    public function CancelTransaction($idTransaction){
        $idT = substr($idTransaction, 0, -1);
        $type = substr($idTransaction, -1);
        $transaction = Transaksi::where('id', $idT)->get()->first();
        if($transaction!=null){
            // dd($transaction);
            $transaction->Status_Transaksi = 'Cancel';
            $transaction->Status_Pembayaran = '-';
            $transaction->Status_Pengiriman = '-';
            $pesan = null;
            $notif = new NotificationController();
            if($transaction->save()){
                $detilTransaction = DB::table('detail__transactions as a')
                ->join('transaksis as b', 'a.Transaksis_id', '=', 'b.id')
                ->where('b.id', $transaction->id)
                ->get();

                foreach($detilTransaction as $product){
                    (new ProductsController())->UpdateStokPlus($product->id_product,$product->qty);
                }

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
        else{
            return redirect('/PageNotFound');
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
                'a.type_transaction',
            )
            ->join('users as b', 'a.id_user', '=', 'b.id_User')
            ->where('a.Status_Pembayaran', 'Done')

            ->get();
            // dd($Transaction);
        return $Transaction;
    }

    public function getAllById($idUser){
        $Transaction = DB::table('transaksis as a')
            ->select(
                'a.id',
                'a.created_at',
                'b.namaUser',
                'a.TotalShopping',
                'a.Shipping',
                'a.Notes',
                'a.type_transaction',
                'a.Status_Transaksi',
                'a.Status_Pembayaran',
            )
            ->join('users as b', 'a.id_user', '=', 'b.id_User')
            ->where('a.id_user',$idUser)
            ->get();
            // dd($Transaction);
        return $Transaction;
    }

    public function CustomTransaction($dataPart,Request $req){
        // dd($req);
        $is1 = strpos($req->dataPart, '-');
        $datas = explode("-", $dataPart);


        $DT = new DetailTransactionController();
        if($is1!=false){
            foreach($datas as $d){
                // dd($d);
                $DT->storePart($d);
            }
        }
        else{
            $DT->storePart($dataPart);
        }

        return $this->store($req, 'Custom');

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
        if(isset($Data[0])){
            
            
                // dd($Data);
                
                $notif = new NotificationController();
                $notifs = $notif->getAllNotif();
                // dd($Data[0]);
                $addr = new AddressController();
                $userData = $addr->getDataById();
                $ST=null;
                // dd($Data);
                if($Data[0]->Status_Pembayaran!='Done'){
                    // dd($Data[0]->snapToken==NULL);
                    if($Data[0]->snapToken==NULL){
                        $ST = $this->GetSnapToken($Data);
                        $Data[0]->snapToken = $ST;
                        $Trs=Transaksi::where('id', $Data[0]->id)->first();
                        $Trs->snapToken = $ST;
                        $Trs->save();
                    }
                    else{
        
                        $ST = $Data[0]->snapToken;
                    }
                }
                else{
                    // dd($Data[0]);
                    $ST = $Data[0]->snapToken;
                }
                $detil = $Data[0]->Address;
        
                // dd($Data);
                if(session('Role')=="Admin"){
                    // $Address = new AddressController();
                    // $detil = $Address->getDetil($Data[0]->id_user);
                    return view('Transaction',['notif'=>$notifs,'data'=>$Data,'userData'=>$userData, 'idT'=>$idT,'Address'=>$detil]);
                }
                else{
        
                    return view('Transaction',['notif'=>$notifs,'data'=>$Data,'userData'=>$userData, 'idT'=>$idT,'snapToken'=>$ST,'Address'=>$detil]);
                }
        }
        else{
            return redirect('/PageNotFound');
        }

    }
    public function PaymentTransaction($snapToken){
        return view('User.Pelanggan.Payment',['snap'=>$snapToken]);
    }

    public function cekStatus($idTransaction){
        if(session('user_id')>0){
            $transaction = Transaksi::where('id', $idTransaction)
            ->latest() // sama dengan orderBy('created_at', 'desc')
            ->first();
            
            $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth",
            ])->get("https://api.sandbox.midtrans.com/v2/".$transaction->id.env('CODE_TRANSACTION')."/status");
            // dd($response);
            $response = json_decode($response->body());
            // dd($response,$transaction);

            // dd($response);
            if($response->status_code!=null){
                // dd($response);
                if(intval($response->status_code)<"300"){
                    // DD(intval($response->status_code)<"300");
                        (isset($response->va_numbers[0]->bank))?
                        $transaction->PaymentMethod = Str::title(Str::replace("_"," ",$response->payment_type))." (".Str::upper($response->va_numbers[0]->bank).")":
                        $transaction->PaymentMethod = Str::title(Str::replace("_"," ",$response->payment_type));
                }
                $notif = new NotificationController();
                //notif to Customer
                $notif->store(2,$transaction->id,session('user_id'));
                
                //notif to Admin
                $notif->store(7,$transaction->id,1);
                $paymentStatus = $response->status_code;
                if($paymentStatus==200){
                    $transaction->Status_Pembayaran = 'Done';
                    $transaction->save();
                }
                else if($paymentStatus==202){
                    if($response->transaction_status=='deny'){
                        //UpdateIdTransaction
                        $newId = $id = DB::table('transaksis')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->value('id');
                        $idOld = $transaction->id; 
                        $transaction->id = $newId;

                        //updateIdTransactionOnDetilTransaction
                        $contDetilTransaction = new DetailTransactionController();
                        $contDetilTransaction->updateIDTransaction($idOld, $transaction->id);  

                        $transaction->snapToken = null;
                        $transaction->Status_Pembayaran = 'Reject By '.$response->payment_type;
                        $transaction->save();
                    }
                }
                elseif ($paymentStatus>200 && $paymentStatus<300) {
                    // dd($response->transaction_status);
                    $transaction->Status_Pembayaran = $response->transaction_status;
                    $transaction->save();
                    // dd('masuk', $transaction,$response);
                }
            }
            // DD($transaction);

            $data = Transaksi::where('id', $idTransaction)->get()->first();
            // dd($response,$transaction,$data);

            return response()->json($data->Status_Pembayaran);
        }
        else{
            return view('OutOfPages');
        }
    }

    public function RedirectNewestTransaction(){
        // dd(session('user_id'));
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
            // dd($response,$transaction);

            // dd($response);
            if($response->status_code!=null){
                // dd($response);
                if(intval($response->status_code)<300){
                        (isset($response->va_numbers[0]->bank))?
                        $transaction->PaymentMethod = Str::title(Str::replace("_"," ",$response->payment_type))." (".Str::upper($response->va_numbers[0]->bank).")":
                        $transaction->PaymentMethod = Str::title(Str::replace("_"," ",$response->payment_type));
                }

                
                $notif = new NotificationController();
                //notif to Customer
                $notif->store(2,$transaction->id,session('user_id'));
                
                //notif to Admin
                $notif->store(7,$transaction->id,1);
                $paymentStatus = $response->status_code;
                if($paymentStatus==200){
                    $transaction->Status_Pembayaran = 'Done';
                    $transaction->save();
                    if(app()->environment('local')){
                        // dd('masuk');
                        return redirect('http://127.0.0.1:8000/Transaction/'.$transaction->id)->with('notif','Pembayaran berhasil');
                    }
                    else{
        
                        return redirect('/Transaction/'.$transaction->id)->with('notif','Pembayaran berhasil');
                    }
                }
                else if($paymentStatus==202){
                    if($response->transaction_status=='deny'){
                        //UpdateIdTransaction
                        $newId = $id = DB::table('transaksis')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->value('id');
                        $idOld = $transaction->id; 
                        $transaction->id = $newId;

                        //updateIdTransactionOnDetilTransaction
                        $contDetilTransaction = new DetailTransactionController();
                        $contDetilTransaction->updateIDTransaction($idOld, $transaction->id);  

                        $transaction->snapToken = null;
                        $transaction->Status_Pembayaran = 'Reject By '.$response->payment_type;
                        $transaction->save();


                        if(app()->environment('local')){
                            // dd('masuk');
                            return redirect('http://127.0.0.1:8000/Transaction/'.$transaction->id)->with('notif','Pembayaran Ditolak oleh Akulaku');
                        }
                        else{
            
                            return redirect('/Transaction/'.$transaction->id)->with('notif','Pembayaran Ditolak oleh Akulaku');
                        }



                        // return redirect('/Transaction/'.$transaction->id)->with('notif','Pembayaran Ditolak oleh Akulaku');   
                    }
                }
                elseif ($paymentStatus>200 && $paymentStatus<300) {
                    // dd($response->transaction_status);
                    $transaction->Status_Pembayaran = $response->transaction_status;
                    $transaction->save();
                    // dd('masuk', $transaction,$response);
                }
            }
            // dd(app()->environment('local'));
            if(app()->environment('local')){
                // dd('masuk');
                return redirect('http://127.0.0.1:8000/Transaction/'.$transaction->id);
            }
            else{

                return redirect('/Transaction/'.$transaction->id);
            }
    }  
    
    public function historyCustomer(){
        if(session('user_id')>0){
            $data = $this->getAllById(session('user_id'));
            // dd($data);
            
            return view('User.Pelanggan.history',['data'=>$data]);
        }
        else{
            return view('OutOfPages');
        }
    }

    // public function DashboardAdmin(){
    //     $TotalEarning = [
    //         [
    //             DB::table('detail__transactions as a')
    //                 ->join('products as b', 'b.id_product', '=', 'a.id_product')
    //                 ->join('transaksis as c', 'a.Transaksis_id', '=', 'c.id')
    //                 ->selectRaw('sum((a.qty * b.price) - (a.qty * b.originalPrice)) as laba')
    //                 ->where('c.Status_Transaksi', 'Acceptted')
    //                 ->whereDay('c.created_at', now()->day)
    //                 ->whereMonth('c.created_at', now()->month)
    //                 ->whereYear('c.created_at', now()->year)
    //                 ->first()
    //         ],
    //         [
    //             DB::table('detail__transactions as a')
    //                 ->join('products as b', 'b.id_product', '=', 'a.id_product')
    //                 ->join('transaksis as c', 'a.Transaksis_id', '=', 'c.id')
    //                 ->selectRaw('sum((a.qty * b.price) - (a.qty * b.originalPrice)) as laba')
    //                 ->where('c.Status_Transaksi', 'Acceptted')
    //                 ->whereMonth('c.created_at', now()->month)
    //                 ->first()
    //         ],
    //         [
    //             DB::table('detail__transactions as a')
    //                 ->join('products as b', 'b.id_product', '=', 'a.id_product')
    //                 ->join('transaksis as c', 'a.Transaksis_id', '=', 'c.id')
    //                 ->where('c.Status_Transaksi', 'Acceptted')
    //                 ->selectRaw('sum((a.qty * b.price) - (a.qty * b.originalPrice)) as laba')
    //                 ->first()

    //         ]
    //     ];

    //     $TotalOrder=[
    //         [
    //             DB::table('transaksis as a')
    //                 ->where('a.Status_Transaksi', 'Acceptted')
    //                 ->whereDay('a.created_at', now()->day)
    //                 ->whereMonth('a.created_at', now()->month)
    //                 ->whereYear('a.created_at', now()->year)
    //                 ->sum('a.TotalShopping')
    //         ],
    //         [
    //             DB::table('transaksis as a')
    //                 ->where('a.Status_Transaksi', 'Acceptted')
    //                 ->whereMonth('a.created_at', now()->month)
    //                 ->sum('a.TotalShopping')

    //         ],[
    //             DB::table('transaksis as a')
    //                 ->where('a.Status_Transaksi', 'Acceptted')
    //                 ->sum('a.TotalShopping')
    //         ]
    //     ];

    //     $TotalTransaksi = DB::table('transaksis as a')
    //         ->where('a.Status_Transaksi', 'Acceptted')
    //         ->count();

    //     $TotalItem = [
    //         [
    //             DB::table('transaksis as a')
    //                 ->join('detail__transactions as b', 'a.id', '=', 'b.Transaksis_id')
    //                 ->where('a.Status_Transaksi', 'Acceptted')
    //                 ->sum('b.qty')
    //         ],[
    //             DB::table('transaksis as a')
    //                 ->join('detail__transactions as b', 'a.id', '=', 'b.Transaksis_id')
    //                 ->where('a.Status_Transaksi', 'Acceptted')
    //                 ->where('a.type_transaction', 'Product')
    //                 ->sum('b.qty')
    //         ],[
    //             DB::table('transaksis')
    //                 ->where('type_transaction', 'Custom')
    //                 ->count()
    //         ],[
    //             DB::table('transaksis as a')
    //                 ->join('detail__transactions as b', 'a.id', '=', 'b.Transaksis_id')
    //                 ->where('a.Status_Transaksi', 'Acceptted')
    //                 ->where('a.type_transaction', 'Custom')
    //                 ->sum('b.qty')
    //         ]
    //     ];
        
    //     $Merge = [$TotalEarning,$TotalOrder, $TotalItem, $TotalTransaksi];




    //     return view('User.Admin.AdminDashboard',['Data'=>$Merge]);
    // }
        
}
