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
        // dd($Transaction);
        $Transaction->save();
        
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        
        $addr = new AddressController();
        $userData = $addr->getDataById();

        
        // ,'notif'=>$notifs
        return redirect('Transaction/'.$Transaction->id);
        
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

    public function toTransaction($idT){
        dd($idT);
        $Data = DB::table('transaksis as a')
        ->join('detail__transactions as b', 'a.id', '=', 'b.Transaksis_id')
        ->join('products as c', 'b.id_product', '=', 'c.id_product')
        ->join('photos as d', 'c.mainPhoto', '=', 'd.id_Photo')
        ->where('a.id', $idT)
        ->select('a.*', 'b.*', 'c.*', 'd.*', 'a.created_at as Deadline')
        ->get();
    
        // dd($Data);
        
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        
        $addr = new AddressController();
        $userData = $addr->getDataById();
        // dd($userData);

        return view('Transaction',['notif'=>$notifs,'data'=>$Data,'userData'=>$userData]);

    }

    public function Bayar(){
        $data = Transaksi::where('Status_Pembayaran', 'Waiting')->get();
        // dd($data);
        return view('apiPembayaran',['data'=>$data]);
    }

    public function payment(Request $req){
        // dd($req->all());
        $transaction = Transaksi::where('id', $req->idTransaction)->get()->first();
        // dd($transaction);
        $transaction->Status_Pembayaran = 'Done';
        $transaction->save();

        return redirect('/Bayar');
        
    }

    public function cekStatus($idTransaction){
        $data = Transaksi::where('id', $idTransaction)->get()->first();
        return response()->json($data->Status_Pembayaran);
    }
}
