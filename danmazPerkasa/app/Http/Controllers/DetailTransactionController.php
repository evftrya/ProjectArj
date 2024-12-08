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
        ->where('a.status','Pending')
        ->get();
        // dd(($product));
        if(isset($products[0]->qty)){
            $old = Detail_Transaction::where('id_Detail_transaction', session("user_id"))->first();
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
            )
            ->where('a.id_User', 1);
            if($wht=='Checkout'){
                $Data->where('a.status','Checkout');
            }
            $Data = $Data->get();
               
         return $Data;
    }

    public function Cart(){
        $data = $this->getAllData(null);
        // dd($data[0]);

        return view('Cart',['data'=>$data]);
    }

    public function UpdateCart(Request $req, $idProduct){
        $product = (new ProductsController())->getDataProduct($idProduct)[0][0];
        // dd($product);
        
        $old = Detail_Transaction::where('id_Detail_transaction', session("user_id"))->first();
        if($old){
            // dd($old);
            $old->qty = $req->qty;
            $old->Total = $old->qty*$product->price;
            if($old->save()){
                return response()->json(['message'=> 'success']);
            };
            // dd($old);
            // $old->Total =  
        }
    }

    public function CheckoutView(){
        $data = $this->getAllData('Checkout');
        // dd($data);
        return view('Checkout',['data'=>$data]);
    }

    public function UpdateStatus($idProduct, $wht){
        $old = Detail_Transaction::where('id_User', session("user_id"))
            ->where('id_product',$idProduct)
            ->first();
        // dd($old);
        if($wht=="1"){
            $old->status = "Checkout";
        }
        else{
            $old->status = "Pending"; 
        }
        if($old->save()){
            return response()->json(['message'=> 'success']);

            // dd('Berhasil');
        }
        
    }
}
