<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Controllers\pro;
use App\Models\Detail_Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductsController;

class DetailTransactionController extends Controller
{
    public function store(Request $req,$idProduct){
        
        if ($req->isMethod('get')) {
            return redirect()->route('page.notfound');
        }
        else{
            if($this->AuthSystem()>0){
                // dd(session('direction'));
                if(session('direction')==null){
                    
                    // dd(session("user_id"));
                    // dd($idProduct);
                    $Cont = new ProductsController();
                    $product = $Cont->getDataProduct($idProduct)[0][0];
                    // echo $product;
                    $total = $product->price*$req->qty;
                    // dd($product->price);
                    
                    $products = DB::table('detail__transactions as a')
                    ->where('a.id_product', $idProduct)
                    ->where('a.id_user', session("user_id"))
                    ->where(function ($query) {
                        $query->where('a.status', 'Pending')
                              ->orWhere('a.status', 'Checkout');
                    })
                    ->whereNull('a.Transaksis_id')
                    ->get();
            
            
                    // dd("ms");
                    // dd(($products)); 
                    if((isset($products[0]->qty))){
                        if($product->stok!=0){
                            $old = Detail_Transaction::where('id_Detail_transaction', $products[0]->id_Detail_transaction)->first();
                            if($old){
                                // dd($old);
                                $old->qty = $old->qty+$req->qty;
                                $old->Total = $old->qty*$product->price;
                                if($old->save()){
                                    return response()->json(['message'=> 'successOld'.$products[0]->qty]);
                                }
                                else{
                                    return response()->json(['message'=> 'false'.$products[0]->id_Detail_transaction]);
                                }
                                // $old->Total =  
                            }
                        }
                        else{
                            return response()->json(['message'=> 'NoStock']);
                            // return response()->json('Nostok 1');
                            // dd('masuk else');
                            
                        }
                        // dd('masuk 2');
                    }
                    // dd('masuk 3');
                    else{
                        if($product->stok!=0){
                            
                            $detil = new Detail_Transaction();
                            $detil->qty = $req->qty;
                            $detil->total = $total;
                            $detil->id_User = session("user_id");
                            // $detil->status = 0;
                            $detil->id_product = $idProduct;
                            // $detil->save();
                            if($detil->save()){
                                return response()->json(['message'=> 'successNew'.$detil->id_Detail_transaction]);
                            }
                            else{
                                return response()->json(['message'=> 'false'.$detil->id_Detail_transaction]);
                            }
                        }
                        else{
                            return response()->json(['message'=> 'NoStock']);
                            // return response()->json('Nostok 2');
                            
                        }
                    }
                    
                    //ISIII------------------------
                }
            }
            else{
                session(['direction' => '/']);
                return response()->json(['message'=> 'false']);
            }

        }    


        
    }

    public function storePart($idPart){
        $Cont = new ProductsController();
        $product = $Cont->getDataProduct($idPart)[0][0];
        $DT = new Detail_Transaction();
        // dd($DT);
        $DT->qty = 1;
        $DT->id_User = session('user_id');
        $DT->id_product = $product->id_product;
        $DT->Status ='Checkout';
        $DT->Total = $product->price;
        $DT->save();
        // dd($product);
    }

    

    public function getAllData($wht){
        // dd(session('user_id'));
        $Data = DB::table('detail__transactions as a')
            ->join('products as b', 'a.id_product', '=', 'b.id_product')
            ->join('photos as c', 'b.mainPhoto', '=', 'c.id_Photo')
            ->select(
                'a.id_Detail_transaction',
                'c.PhotosName',
                'b.nama_product',
                'b.stok',
                'b.price',
                'a.qty',
                DB::raw('ROUND(b.weight / 1000, 2) as weight'),
                'b.id_product',
                'a.id_User',
                'b.stok',
            )->where('a.id_User', session('user_id'));

            if($wht=='Checkout'){
                $Data->where('a.status','Checkout');
            }
            else if ($wht=='TempCheckout') {
                $Data->where('a.status','TempCheckout');
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
        // dd($cont->AuthSystem()>0);


        if($this->AuthSystem()>0){
            // dd('masuk');
            if(session('direction')!=null){
                $tempDirection = session('direction');
                session(['direction' => null]);
                return redirect($tempDirection);

            }
            else{
                // dd(session('direction'));
                $data = $this->getAllData('Pending');
                $notif = new NotificationController();
                $notifs = $notif->getAllNotif();
                // ,'notif'=>$notifs
                // dd($data);
                return view('User.Pelanggan.Cart',['data'=>$data,'notif'=>$notifs]);
            }
        }
        else{
            session(['direction' => '/Cart']);
            return redirect('/Login');
        }

        
        
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
            // dd(session('direction'));
                // dd(session());
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
                foreach ($data as $product) {
                    (new ProductsController())->UpdateStokMinus($product->id_product,$product->qty);
                }
                // dd($data);
        
                $addr = new AddressController();
                $userData = $addr->getDataById();
                // dd($userData);
                $cont = new Controller();
                // $ships = ($cont->getOngkir($userData[0]->city_id));
                // dd(json_decode($userData[0]->ShippingRate['rajaongkir']['results']));
                $ships = [($userData[0]->ShippingRate), json_decode($userData[0]->ShippingRate)];

                // $a[0]['costs'] as $b
                // dd($ships[1][0][0]->costs);
                // dd('ship1',$ships[0]["costs"]);
                // dd('ship2',$ships2);
                $shipjs = $ships[0];
                $ship = $ships[1];
                $notif = new NotificationController();
                $notifs = $notif->getAllNotif();
                $routeChekcout = 'Default';
                // 'routeChekcout'=>$routeChekcout,
                // ,'notif'=>$notifs
                // dd($ship);
                // dd($data);
                return view('User.Pelanggan.Checkout',['routeChekcout'=>$routeChekcout,'data'=>$data,'userData'=>$userData[0],'ship'=>$ship,'shipjs'=>$shipjs,'notif'=>$notifs,'Back'=>'Cart']);
        
    }

    public function CheckoutViewDirect($idProduct){
        if($this->AuthSystem()>0){
            // dd(session('direction'));
            if(session('direction')!=null){
                $tempDirection = session('direction');
                session(['direction' => null]);
                return redirect($tempDirection);
            }
            else{
                if($idProduct!='null'){
        
                    $product = (new ProductsController())->getDataProduct($idProduct)[0][0];   
        
                    // dd($product);
                    $new = new Detail_Transaction();
                    $new->qty = 1;
                    $new->id_product = $idProduct;
                    $new->Total = $product->price;
                    $new->Status = 'TempCheckout';
                    $new->id_User = session("user_id");
                    $new->save();
                }
                $data = $this->getAllData('TempCheckout');
        
                $addr = new AddressController();
                $userData = $addr->getDataById();
                // dd($userData);
                // dd($userData[0]->city_id);
                $cont = new Controller();
                // $ships = ($cont->getOngkir($userData[0]->city_id));
                $ships = [($userData[0]->ShippingRate), json_decode($userData[0]->ShippingRate)];


                $shipjs = $ships[0];
                $ship = $ships[1];
                $notif = new NotificationController();
                $notifs = $notif->getAllNotif();
                $routeChekcout = 'Temp';
                // 
                // ,'notif'=>$notifs
                // dd($ship);
                return view('User.Pelanggan.Checkout',['routeChekcout'=>$routeChekcout,'data'=>$data,'userData'=>$userData[0],'ship'=>$ship,'shipjs'=>$shipjs,'notif'=>$notifs,'Back'=>'Index']);
            }
        }
        else{

            session(['direction' => '/Cart']);
                return redirect('/login');
            // dd($Newqty);
        }
    }

    public function deleteTempCheckout(){
        if(session('user_id')>0){

            $data = DB::table('detail__transactions')
            ->where('Status', 'TempCheckout')
            ->where('id_User', session('user_id'))
            ->get();
    
            foreach($data as $d){
                // dd($d);
                $this->DeleteCart($d->id_Detail_transaction);
            }
        }
        else{
            return view('OutOfPages');
        }
        // dd($data);
    
    }

    public function CheckoutViewCustom(Request $req){
        // dd($req);
        $is1 = strpos($req->dataPart, '-');
        $dataPart = explode("-", $req->dataPart);
        $data = [];
        $product = new ProductsController();
        if($is1!=false){
            foreach ($dataPart as $item) {
                // dD($item);  
                // dd($product->getAllDataProductById($item)->original[0][0]);
                $add = $product->getAllDataProductById($item)->original[0][0];
                $add->qty = 1;
                $add->weight = $add->weight/1000;
                $add->type_transaction = 'Custom';
                $data[] = 
                    $add;
                ;
            }
        }
        else{
            // dd($dataPart);
            // $add = $product->getAllDataProductById($dataPart);
            $add = $product->getAllDataProductById($dataPart)->original[0][0];
            // dd($add);

            $add->qty = 1;
            $add->weigth_final = $add->weight/1000;
            $add->type_transaction = 'Custom';
            // dd($add);
            $data[] = 
                $add;
            ;
        }
        // dd($data);
        // dd($data[0][0]->original[0][0]);
        $routeChekcout = 'Custom';

        $addr = new AddressController();
        $userData = $addr->getDataById();

        $cont = new Controller();
        // $dataOngkir = json_decode($userData[0]->ShippingRate);
        // dd($dataOngkir);
        $ships = [($userData[0]->ShippingRate), json_decode($userData[0]->ShippingRate)];
        $shipjs = $ships[0];
        $ship = $ships[1];
        // $ship = $ships[1]['rajaongkir']['results'];
        // dd($ship);
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();



        return view('User.Pelanggan.Checkout',['routeChekcout'=>$routeChekcout,'data'=>$data,'userData'=>$userData[0],'ship'=>$ship,'shipjs'=>$shipjs,'notif'=>$notifs,'dataPart'=>$req->dataPart,'Back'=>'Custom']);
    }



    public function UpdateStatus($idProduct, $wht){
        $old = Detail_Transaction::where('id_User', session("user_id"))
            ->where('id_product',$idProduct)
            ->where('Transaksis_id',null)
            ->first();
        // dd($old);
        if($wht=="1"){
            if($old!=null){
                $old->status = "Checkout";
            }
            else{
                return response()->json(['message'=> 'There Something Transaction Not Done Yet with the Same Product']);
            }
        }
        elseif($wht=='WFP'){
            $old->status = $wht;
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

    public function DeleteCart($id){
        // dd($req);

        $data = Detail_Transaction::where('id_Detail_transaction', $id)->first()->delete();
        return redirect ("/Cart");
        

    }

    public function getDetilTransactionsByIdTransaction($idTransaction){
        $data = DB::table('detail__transactions as a')
        ->join('products as b', 'a.id_product', '=', 'b.id_product')
        ->where('a.Transaksis_id', 37)
        ->select('a.*', 'b.*')
        ->get();
        // dd($data);
        return ($data);
        
    }
    public function updateIDTransaction($OldId, $newId){
        $DetilTransactions = Detail_Transaction::where('Transaksis_id', 3)->get();
        // dd($DetilTransactions);
        foreach($DetilTransactions as $DetilTransaction){
            
            $object = Detail_Transaction::where('id_Detail_transaction', $DetilTransaction->id_Detail_transaction)->first();
            $object->Transaksis_id = $newId;
            $object->save();
        }
    }
}
