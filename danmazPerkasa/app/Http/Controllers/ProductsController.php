<?php

namespace App\Http\Controllers;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\PhotosController;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    //
    // public function store(Request $req){
    //     // dd($req->file('foto2'));
    //     // dd($req->TotalPhoto);
    //     for($i=1; $i<=$req->TotalPhoto;$i++){
    //         dd( $req->files[('foto'.$i)]->getClientOriginalName());
    //     }
    // }

    public function store(Request $req){
        // dd($req);

        $Product = new Products();
        // $Product->id_product = 1;
        $Product->nama_product = $req->ProductName;
        $Product->stok = $req->stock;
        $Product->price = $req->ProductPrice;
        $Product->Category = $req->product;
        $Product->detail_product = $req->Description;
        $Product->Features = $req->Features;
        $Product->type = 'Product';
        $Product->save();
        $id = $Product->id_product;

        $photo = new PhotosController();
        $main = null;
        for($i = 1; $i <= $req->TotalPhoto; $i++){
            // store(Request $req, $idProduct, $number)
            if('foto'.$i==$req->mainPhoto){
                // dd('foto'.$i==$req->mainPhoto);
                $main = $photo->store($req, $id, $i);
            }
            else{
                $photo->store($req, $id, $i);
            }

        }
        $Product->mainPhoto = $main;
        $Product->save();

        dd('stop');
        
        return redirect('/Product-Manage');
    }

    public function getData($wht){

        $products = DB::table('products as a')
            ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.stok',
                'a.Category',
                'a.detail_product',
                'a.Features',
                'b.PhotosName',
                'a.price'
            );
            
            if($wht!=null){
                $products->where('a.Category', $wht);
            }
            $products = $products->get();
        
        return $products;
    }
    public function getDataProduct($idProduct){
        $product = DB::table('products as a')
            ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.stok',
                'a.detail_product',
                'a.Features',
                'b.PhotosName',
                'a.price'
        )->where('a.id_product', $idProduct)
        ->get();
        
        $photos = DB::table('photos as a')
        ->select(
            'a.id_photo',
            'a.PhotosName',
        )->where('a.id_product', $idProduct)
         ->where('a.isMain', null)->get(); 

        // dd($photos);
        // dd($product);
        return [$product, $photos];

    }
    
    public function ProductManage(){
        $data = $this->getData(null);

        // dd($data[0]->id_product);
        // dd($data[0]);
        return view('ManageProduct',['routeForm'=>'/add-product', 'data'=>$data]);
    }

    public function Product($wht){
        $send=null;
        if($wht!='AllProduct'){
            $send = $wht;
        }
        $data = $this->getData($send);
        // dd($data);
        return view ('Product', ['data'=>$data,'wht'=>$wht]);
    }
    // Route::get('/Detil-Product', function(){
    //     return view('/ProductDetil');
    // });
    // Route::get('/Detil-Product/{id}',[ProductsController::class,'DetilProducts']);

    public function DetilProducts($idProduct){
        $data = ($this->getDataProduct($idProduct));
        // dd($data);
        return view('/ProductDetil',['product'=>$data[0][0],'photos'=>$data[1]]);

    }
}
