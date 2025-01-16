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

    public function store(Request $req, $wht ){
        // dd($req);

        $Product = new Products();
        // $Product->id_product = 1;

        
        $Product->nama_product = $req->ProductName;
        $Product->stok = $req->stock;
        $Product->price = $req->ProductPrice;
        $Product->color = $req->ProductColor;
        $Product->shortQuotes = $req->shortQuotes;
        $Product->detail_product = $req->Description;
        $Product->weight= $req->weight;
        $Product->type = 'Part';
        $Product->Category = $req->product;
        if($wht=='Product'){
            $Product->type = 'Product';
            $Product->Features = $req->Features;
        }
        $Product->save();
        $id = $Product->id_product;

        if($wht=='Product'){
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
        }
        $from = null;
        ($wht=='Part') ? $from = 'Manage/Product/Part' : $from = 'Manage/Product/Product';
        
        return redirect('/'.$from);
    }

    public function getData($wht, $from){
        // dd($wht);
        $products = DB::table('products as a')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.stok',
                'a.isContent',
                'a.Category',
                'a.detail_product',
                'a.weight',
                'a.price'
            );
            // dd($wht);
            if($from=='Product'){
                $products = $products->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
                            ->addSelect('b.PhotosName',
                                'a.Features',
                    );
            }
            
            if($wht!=null && $wht!='productManage'){
                $products->where('a.Category', $wht);
            }

            if($wht!='productManage'){
                $products = $products->where('a.stok','>',0 );
            }
            // dd($from);
            if($from == 'Product'){
                $products = $products->where('a.type','Product' );
            }
            else{
                $products = $products->where('a.type','Part' );
            }
            $products = $products->get();
            // dd($products);
        return $products;
    }
    public function getDataProduct($idProduct){
        $product = DB::table('products as a')
            ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.stok',
                'a.isContent',
                'a.detail_product',
                'a.Features',
                'b.PhotosName',
                'a.price'
        )->where('a.id_product', $idProduct)
        // ->where('a.stok','>',0 )
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

    public function getAllDataProductById($id){
        $photos = DB::table('photos as a')
        ->select(
            'a.id_Photo',
            'a.PhotosName',
            'isMain'
        )->where('a.id_product', $id)
        ->get();

        $product = DB::table('products as a')
            ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.stok',
                'a.type',
                'a.shortQuotes',
                'a.isContent',
                'a.color',
                'a.weight',
                'a.weight',
                'a.detail_product',
                'a.Features',
                'b.PhotosName',
                'a.price'
        )->where('a.id_product', $id)
        // ->where('a.stok','>',0 )
        ->get();

        $data=[$product, $photos];
        
        return response()->json($data);
    }
    
    public function ProductManage($from){
        // dd($from);
        $data = $this->getData('productManage',$from);

        // dd($data[0]->id_product);
        $route = null;
        $view = null;
        $from!='Part' ? $Route='/addproduct' : $Route='/addPart';
        $from!='Part' ? $view='ManageProduct' : $view='ManagePart';
        // dd($data);
        // dd(("User.Admin.".$view));
        return view(("User.Admin.".$view),['routeForm'=>$Route, 'data'=>$data]);
    }

    public function Product($wht){
        $send=null;
        if($wht!='AllProduct'){
            $send = $wht;
        }
        $data = $this->getData($send,'Product');
        // dd($data);
        return view ('User.Pelanggan.Product', ['data'=>$data,'wht'=>$wht]);
    }
    // Route::get('/Detil-Product', function(){
    //     return view('/ProductDetil');
    // });
    // Route::get('/Detil-Product/{id}',[ProductsController::class,'DetilProducts']);

    public function DetilProducts($idProduct){
        $data = ($this->getDataProduct($idProduct));
        // dd($data);
        return view('/User.Pelanggan.ProductDetil',['product'=>$data[0][0],'photos'=>$data[1]]);

    }

    public function CheckoutProduct($qty, $idProduct){
        $old = Products::where('id_product', $idProduct)->first();
        // dd($old->stok);
        // dd(gettype($qty));
        $old->stok =($old->stok-$qty);
        // dd($old->stok);
        $old->save();
        // dd($old->stok);
    }

    public function delete($idProduct){
        $product = Products::where('id_product', $idProduct)->first()->delete();
        return redirect('/Manage/Product/Product');
    }
    public function updateProduct(Request $req, $idProduct){
        $this->update($req, $idProduct,"Product");
        return redirect('/Manage/Product/Product');

    }

    public function update(Request $req, $idProduct,$from){
        // dd($req);
        $Product = Products::where('id_product', $idProduct)->first();
        $oldMain = $Product->mainPhoto;
        // dd($oldMain);
        $Product->nama_product = $req->ProductName;
        $Product->stok = $req->stock;
        $Product->price = $req->ProductPrice;
        $Product->color = $req->ProductColor;
        $Product->detail_product = $req->Description;
        $Product->weight= $req->weight;
        $Product->type = $from;
        $Product->Category = $req->product;
        $Product->save();

        if($from=="Product"){
            $photo = new PhotosController();
            $main = null;
            for($i = 0; $i < $req->TotalPhoto; $i++){
                // dd('foto'.$i==$req->mainPhoto);
                // if($photo->cekExist())
                if($req->file('foto'.$i+1)!=null){
                    // dd('masuk');
                    $tes = $photo->store($req, $Product->id_product, $i+1);
                    // dd($tes);
                }


                if($req->mainPhoto!='foto0'){
                    // dd("masui");
                    $main = substr($req->mainPhoto,4);
                    // dd($main);
                    $allPhotos = $photo->getSortPhotos($Product->id_product);
                    // dd($allPhotos);
                    for($i=0;$i<count($allPhotos);$i++){
                        if($main==$i+1){
                            // dd(",aasuk");
                            $Product->mainPhoto = $allPhotos[$i]->id_Photo; 
                            $photo->turnMain($allPhotos[$i]->id_Photo,$idProduct);
                            $Product->save();
                        }
                    }
                }

            }

        }
    }
    public function ContentOn($idProduct){
        return $this->ChangeContent($idProduct,'on');
    }
    public function ContentOff($idProduct){
        return $this->ChangeContent($idProduct,'off');
    }

    public function ChangeContent($idProduct,$wht){
        $data = null;
        ($wht=='on') ? $data=1 : $data=0;
        DB::table('products')
            ->where('id_Product', $idProduct)
            ->update(['isContent' => $data]);

        return response()->json(['message' => 'success']);

    }

    public function LandingPage(){
        $Contens = DB::table('products as a')
        ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
        ->where('a.isContent', 1)
        ->select('a.*', 'b.*') // Pilih kolom sesuai kebutuhan
        ->get();

        return view('landingpage',['Content'=>$Contens]);
    }

    
    
}
