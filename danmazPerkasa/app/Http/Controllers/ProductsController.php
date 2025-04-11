<?php

namespace App\Http\Controllers;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\PhotosController;
use App\Models\Photos;
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

            $photo = new PhotosController();
            $main = null;
            for($i = 1; $i <= $req->TotalPhoto; $i++){
                // store(Request $req, $idProduct, $number)
                // if($wht=='Product'){

                    if('foto'.$i==$req->mainPhoto){
                        // dd('foto'.$i==$req->mainPhoto);
                        $main = $photo->store($req, $id, $i,$wht);
                    }
                    else{
                        $photo->store($req, $id, $i,$wht);
                    }
                // }
                // else{
                    // $photo->store($req, $id, $i,$wht);
                    // dd('work');
                // }
    
            $Product->mainPhoto = $main;
            $Product->save();
        }
        $from = null;
        ($wht=='Part') ? $from = 'Manage/Product/Part' : $from = 'Manage/Product/Product';
        // dd('stop bntr');
        // dd($Product);
        $notif = new NotificationController();
        $allUser = DB::table('users')
            ->select('id_User')
            ->where('role', 'User')
            ->get();
        foreach($allUser as $u){
            $notif->store(1,$Product->id_product,$u->id_User);
        }
        // dd($allUser);
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
                if($wht=='AllProduct'){
                    return $products->get();
                }
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
        return $products;
    }
    public function getDataProduct($idProduct){
        $product = DB::table('products as a')
            ->leftjoin('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.stok',
                DB::raw('ROUND(a.weight / 1000, 2) as weight_kg'),
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

    public function getAllPart(){
        $data = DB::table('products as a')
        ->join('categorypart as b', 'a.Category', '=', 'b.id')
        ->select('a.*', 'b.category as category_name', 'b.area as category_description')
        ->get();

        $area = DB::table('categorypart as a')
        ->select('a.Area')
        ->distinct()
        ->get();

        // dd($area);
        $category = DB::table('categorypart as a')
        ->select('a.CAtegory', 'a.Area')
        ->distinct()
        ->get();

        return [$data,$area,$category];
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

    public function getAllDataPartById($id){

        $product = DB::table('products as a')
            ->LeftJoin('photos as b', 'a.id_product', '=', 'b.id_product')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.Category',
                'a.stok',
                'a.type',
                'a.color',
                'a.weight',
                'a.weight',
                'a.detail_product',
                'a.Features',
                'b.PhotosName',
                'a.price'
        )
        ->where('a.id_product', $id)
        ->where('a.type','Part' )
        ->get();
        // dd($product);

        // $data=[$product];
        // dd($data);
        
        return response()->json($product);
    }

    
    public function ProductManage($from){
        $from!='Part' ? $data = $this->getData('productManage',$from) : $data=$this->getAllPart();
        // dd($data);
        // dd($data[0]->id_product);
        $Route = null;
        $view = null;
        $category = null;
        $TemplateRoute=null;
        if($from=='Product'){
            $TemplateRoute = '/viewProduct/';
            $Route='/addproduct';
            $view='ManageProduct';
            $category=null;
        }
        elseif ($from=='Part') {
            $category=$this->GetPartCategory();
            $view='ManagePart';
            $Route='/addPart';
            $TemplateRoute = '/viewProduct/';
        }
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        // dd(("User.Admin.".$view));
        // dd($category);
        // dd($data);
        return view(("User.Admin.".$view),['routeForm'=>$Route, 'data'=>$data,'notif'=>$notifs,'Category'=>$category,'TemplateRoute'=>$TemplateRoute]);
    }

    public function GetPartCategory(){
        $data = DB::table('categorypart')->get();
        return $data;
    }

    public function Product($wht){
        
        $send=null;
        if($wht!='AllProduct'){
            $send = $wht;
        }
        $data = $this->getData($send,'Product');
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        return view ('User.Pelanggan.Product', ['data'=>$data,'wht'=>$wht, 'forSearch'=>$wht,'notif'=>$notifs]);
    }

    public function ViewProductAdmin($id){
        $data = ($this->getDataProduct($id));
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        $header = true;
        // ,'notif'=>$notifs
        // dd($data);
        // dd($data[0]);
        // dd($data);
        if($data[0]!=null){
            return view('/User.Admin.viewProduct',['isNull'=>0,'product'=>$data[0][0],'photos'=>$data[1],'notif'=>$notifs,'header'=>$header]);
        }
        return view('/User.Admin.viewProduct',['isNull'=>1]);
    }
    // Route::get('/Detil-Product', function(){
    //     return view('/ProductDetil');
    // });
    // Route::get('/Detil-Product/{id}',[ProductsController::class,'DetilProducts']);

    public function DetilProducts($idProduct){
        $data = ($this->getDataProduct($idProduct));
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        // dd($data[0]);
        // dd($data);
        if($data[0]!=null){
            return view('/User.Pelanggan.ProductDetil',['isNull'=>0,'product'=>$data[0][0],'photos'=>$data[1],'notif'=>$notifs]);
        }
        return view('/User.Pelanggan.ProductDetil',['isNull'=>1]);
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
    public function deleteProduct($idProduct){
        return $this->delete($idProduct,'Product');
    }

    public function deletePart($idProduct){
        return ($this->delete($idProduct,'Part'));

    }

    public function delete($idProduct,$wht){
        $product = Products::where('id_product', $idProduct)->first();
        // $product = Products::where('id_product', $idProduct)->first()->delete();
        // dd($product);
        $name = $product->nama_product;
        $product->delete();
        return redirect('/Manage/Product/'.$wht)->with('pesan', "Delete ".$name." Successfully");
    }
    public function updateProduct(Request $req, $idProduct){
        $this->update($req, $idProduct,"Product");
        return redirect('/Manage/Product/Product');
    }

    public function updatePart(Request $req, $idProduct){
        // dd($req->foto1);
        $this->update($req, $idProduct,"Part");
        return redirect('/Manage/Product/Part');
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

        $photo = new PhotosController();
        $main = null;
        for($i = 0; $i < $req->TotalPhoto; $i++){
            // dd('foto'.$i==$req->mainPhoto);
            // if($photo->cekExist())
            
            
            if($from=="Product"){
                if($req->file('foto'.$i+1)!=null){
                    // dd('masuk');
                    $tes = $photo->store($req, $Product->id_product, $i+1,'Product');
                    // dd($tes);
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
            else{
                if($req->file('foto1')!=null){
                    // dd('masuk');
                    $photo = Photos::where('id_product', $idProduct)->first();
                    $name = pathinfo($req->file('foto1')->store('images', 'public'), PATHINFO_BASENAME);
                    if(!$photo){
                        $photo = new Photos();
                        $photo->id_product = $idProduct;
                        $photo->isMain = 1;
                        $photo->PhotosName = $name;
                    }
                    else{
                        Photos::where('id_product', $idProduct)
                        ->update(['PhotosName' => $name]);    
                    }
                    // dd($photo);
                        // $photo->PhotosName = pathinfo($req->file('foto1')->store('images','public'), PATHINFO_BASENAME);
                        // $photo->forceFill(['PhotosName' => pathinfo($req->file('foto1')->store('images', 'public'), PATHINFO_BASENAME)]);
                    // dd($photo);
                    $photo->save();
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
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        if(session('Role')!='Admin'){
            $Contens = DB::table('products as a')
            ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            ->where('a.isContent', 1)
            ->select('a.*', 'b.*') // Pilih kolom sesuai kebutuhan
            ->get();
    
            $Special = $this->refresh();
            // ,'notif'=>$notifs
            return view('landingpage',['Content'=>$Contens, 'Special'=>$Special,'notif'=>$notifs]);    
        }
        else{
            return view('User.Admin.dashboard',['notif'=>$notifs]);
        }
    }
    public function refresh(){
        // DB::table('products')->update(['isSpecial' => null]);
        

        // $news = $this->getDataNew();
        // foreach ($news as $item) {
        //     $this->setNew($item->id_product);
        // }

        return $this->getDataRefresh();
    }

    public function getDataRefresh(){
        $products = DB::table('products as a')
            ->join('photos as b', 'b.id_Photo', '=', 'a.mainPhoto')
            ->whereNotNull('a.isSpecial')
            ->select('a.*', 'b.*') // Optional: Select specific columns if needed
            ->get();
            // dd($products);
        return $products;
    }
    public function getDataNew(){
        $TopNew = $products = DB::table('products as a')
        ->orderBy('a.created_at', 'desc')
        ->limit(3)
        ->get();
        return $TopNew;
    }

    public function setNew($idProduct){
        $product = Products::where('id_product', $idProduct)->first();
        $product->isSpecial = 'NEW';
        $product->save();
    }

    public function search(Request $req,$wht){
        // dd($req);
        $send=$wht;
        // dd($wht);
        $data = $this->getData($send,'Product');
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        return view ('User.Pelanggan.Product', ['data'=>$data,'wht'=>$wht,'search'=>$req->search,'forSearch'=>$wht,'notif'=>$notifs]);
    }

    public function Custom(){
        $Parts = DB::table('products as a')
        ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
        ->where('a.type', 'Part')
        ->select('a.*', 'b.*')
        ->get();
    
        $area = DB::table('categorypart')
            ->select('Area')
            ->distinct()
            ->get();

        $category = DB::table('categorypart')->get();
        // $Parts = Products::where('type', 'Part')->get();
        // dd('Parts',$Parts,'Areas',$area,'Categorys',$category);
        return view('User.Pelanggan.Custom', ['Parts'=>$Parts,'Areas'=>$area,'Categorys'=>$category]);
    }

    
    
}
