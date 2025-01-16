<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detail_Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductsController;

class DetailTransactionController extends Controller
{
    public function store(Request $req,$idProduct){
        // dd($idProduct);
        $Cont = new ProductsController();
        $product = $Cont->getDataProduct($idProduct)[0][0];
        // echo $product;
        $total = $product->price*$req->qty;
        // dd($product->price);
        
        $products = DB::table('detail__transactions as a')
            ->select(
                'a.id_Detail_transaction',
                'a.qty',
                'a.Total',
        )->where('a.id_product', $idProduct)
        ->where('a.id_user',session("user_id"))
        ->where('a.status','Pending')->orWhere('a.status','Checkout')
        ->where('a.Transaksis_id',NULL)
        ->get();


        // dd("ms");
        // dd(($products)); 
        if(isset($products[0]->qty)){
            $old = Detail_Transaction::where('id_Detail_transaction', $products[0]->id_Detail_transaction)->first();
            if($old){
                // dd($old);
                $old->qty = $old->qty+$req->qty;
                $old->Total = $old->qty*$product->price;
                if($old->save()){
                    return response()->json(['message'=> 'success']);
                };
                // $old->Total =  
            }
        }
        else{
            $detil = new Detail_Transaction();
            $detil->qty = $req->qty;
            $detil->total = $total;
            $detil->id_User = session("user_id");
            // $detil->status = 0;
            $detil->id_product = $idProduct;
            // $detil->save();
                if($detil->save()){
                    return response()->json(['message'=> 'success']);
                }
        }
        
        
    }

    public function getAllData($wht){
        $Data = DB::table('detail__transactions as a')
            ->join('products as b', 'a.id_product', '=', 'b.id_product')
            ->join('photos as c', 'b.mainPhoto', '=', 'c.id_Photo')
            ->select(
                'a.id_Detail_transaction',
                'c.PhotosName',
                'b.nama_product',
                'b.price',
                'a.qty',
                'b.id_product',
                'a.id_User',
                'b.stok',
            )->where('a.id_User', session('user_id'));

            if($wht=='Checkout'){
                $Data->where('a.status','Checkout');
            }
            else if($wht=='Pending'){
                $Data->where(function($query){
                    $query->where('a.status','Pending')
                    // ->where('a.id_User', session('user_id'))
                    ->orWhere('a.status','Checkout');
                });
                
            }
            $Data = $Data->get();
            // dd($Data);
            
         return $Data;
    }

    public function Cart(){
        $data = $this->getAllData('Pending');
        // dd($data);

        return view('User.Pelanggan.Cart',['data'=>$data]);
    }

    public function UpdateCart(Request $req, $idProduct,$idDT){
        // dd($req);
        $product = (new ProductsController())->getDataProduct($idProduct)[0][0];
        // dd($product);
        
        $old = Detail_Transaction::where('id_Detail_transaction', $idDT)->first();
        // dd($old);
        if($old){
            // dd($old);
            $old->qty = $req->qty;
            $old->Total = $old->qty*$product->price;
            if($old->save()){
                return response()->json(['message'=> 'success']);
            }; 
        }
    }

    // public function AddCart()

    public function CheckoutView($idProduct, $Newqty){
        // dd($Newqty);
        if($idProduct!='null' && $Newqty!='null'){
            $Data = DB::table('detail__transactions as a')
            ->select('*')
            ->where('id_User', session('user_id'))
            ->update(['Status'=> 'Pending']);

            $Newqty = intval($Newqty);
            // dd($Newqty);
            // $this->UpdateStatus($wht,null);
            $this->Cart();
            $product = (new ProductsController())->getDataProduct($idProduct)[0][0];   
            // dd($product);     
            $old = Detail_Transaction::where('id_product', $idProduct)
                                        ->where('id_User', session('user_id'))
                                        ->first();
            // dd($old);
            if($old){
                $old->qty = $Newqty;
            }
            else{
                $detil = new Detail_Transaction();
                $detil->qty = $Newqty;
                $detil->id_User = session("user_id");
                $detil->id_product = $idProduct;
                $old = $detil;
            }
            $old->Total = $Newqty*$product->price;
            $old->save();
            $this->UpdateStatus($idProduct, '1');
        }
        $data = $this->getAllData('Checkout');
        // dd($data);
        return view('User.Pelanggan.Checkout',['data'=>$data]);
    }



    public function UpdateStatus($idProduct, $wht){
        $old = Detail_Transaction::where('id_User', session("user_id"))
            ->where('id_product',$idProduct)
            ->where('Transaksis_id',null)
            ->first();
        // dd($old);
        if($wht=="1"){
            $old->status = "Checkout";
        }
        elseif($wht=='donePayment'){
            $old->status = "Done";
        }
        else{
            $old->status = "Pending"; 
        }
        if($old->save()){
            return response()->json(['message'=> 'success']);

            // dd('Berhasil');
        }
        
        
    }

    public function SetTransaction($idDetil, $idTransaksi){
        $old = Detail_Transaction::where('id_Detail_transaction', $idDetil)->first();
        // dd($old);
        $old->Transaksis_id = $idTransaksi;
        $old->save();
    }

    public function DeleteCart(Request $req, $id){
        // dd($req);

        $data = Detail_Transaction::where('id_Detail_transaction', $id)->first()->delete();
        return redirect ("/Cart");
        

    }
}
