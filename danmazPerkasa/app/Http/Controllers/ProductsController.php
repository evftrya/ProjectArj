<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\PhotosController;
use App\Models\category_part;
use App\Models\category_product;
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

    public function store(Request $req, $wht)
    {
        // dd($req);
        // dd($req);

        $Product = new Products();
        // $Product->id_product = 1;


        $Product->nama_product = $req->ProductName;
        $Product->stok = $req->stock;
        $Product->price = $req->ProductPrice;
        $Product->originalPrice = $req->originalPrice;
        $Product->color = $req->ProductColor;
        $Product->shortQuotes = $req->shortQuotes;
        $Product->detail_product = $req->Description;
        $Product->weight = $req->weight;
        $Product->type = 'Part';
        // $Product->Category = $req->product;
        if ($wht == 'Product') {
            $Product->type = 'Product';
            $Product->Features = $req->Features;
        }
        $Product->save();
        $id = $Product->id_product;

        $photo = new PhotosController();
        $main = null;
        for ($i = 1; $i <= $req->TotalPhoto; $i++) {
            // store(Request $req, $idProduct, $number)
            // if($wht=='Product'){
            // if($wht!='Part'){
            if ('foto' . $i == $req->mainPhoto) {
                // dd('foto'.$i==$req->mainPhoto);
                $main = $photo->store($req, $id, $i, $wht);
            } else {
                if ($wht == 'Part') {
                    $main = $photo->store($req, $id, $i, $wht);
                } else {
                    $photo->store($req, $id, $i, $wht);
                }
            }
            // }
            // else{
            // $main = $photo->store($req, $id, $i, $wht);
            // }


            // }
            // else{
            // $photo->store($req, $id, $i,$wht);
            // dd('work');
            // }

            $Product->mainPhoto = $main;
            $Product->save();

            if ($wht == 'Product') {
                $category = new category_product();
                $category->id_product = $Product->id_product;
                $category->category_name = $req->product;
                $category->save();
            } elseif ($wht == 'Part') {
                $category = new category_part();
                $category->id_part = $Product->id_product;
                $category->id_category_part = $req->product;
                $category->save();
            }
        }
        $from = null;
        ($wht == 'Part') ? $from = 'Manage/Product/Part' : $from = 'Manage/Product/Product';
        // dd('stop bntr');
        // dd($Product);
        $notif = new NotificationController();
        $allUser = DB::table('users')
            ->select('id_User')
            ->where('role', 'User')
            ->get();
        foreach ($allUser as $u) {
            $notif->store(1, $Product->id_product, $u->id_User);
        }
        // dd($allUser);
        return redirect('/' . $from);
    }

    public function getData($wht, $from)
    {
        // dd($wht);
        $products = DB::table('products as a')
            ->select(
                'a.id_product',
                'a.nama_product',
                'a.stok',
                'a.type',
                'a.isContent',
                'a.detail_product',
                'a.weight',
                'a.price'
            );



        // dd($products);
        // dd($wht);
        if ($from == 'Product') {
            $products = $products->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
                ->addSelect(
                    'b.PhotosName',
                    'a.Features',
                );
            if ($wht == 'AllProduct') {
                return $products->get();
            }
        }


        if ($wht != 'productManage') {
            $products = $products->where('a.stok', '>', 0);
        }
        // dd($from);
        if ($from == 'Product') {
            $products = $products->where('a.type', 'Product');
        } else {
            $products = $products->where('a.type', 'Part');
        }

        if ($from == 'Product') {

            $products->join('category_products as m', 'm.id_product', '=', 'a.id_product')
                ->addSelect('m.category_name as Category');
        }

        if ($wht != null && $wht != 'productManage') {
            $products->where('m.category_name', '=', $wht);
        }
        $products = $products->get();
        // dd($products);
        // dd($products);
        return $products;
    }
    public function getDataProduct($idProduct)
    {
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
                'a.type',
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

    public function getAllPart()
    {
        $data = DB::table('products as a')
            ->join('category_parts as b', 'a.id_product', '=', 'b.id_part')
            ->join('ref_category_parts as c', 'c.id_category_part', '=', 'b.id_category_part')
            ->select('a.*', 'c.Category as category_name', 'c.area as category_description', 'c.Types as category_types')
            // ->select('a.*', 'b.*')
            ->get();
        // dd($data);

        $area = DB::table('category_parts as b')
            ->join('ref_category_parts as c', 'c.id_category_part', '=', 'b.id_category_part')

            ->select('c.Area')
            ->distinct()
            ->get();

        // dd($area);
        $category = DB::table('ref_category_parts as c')
            ->select('c.CAtegory', 'c.Area', 'c.id_category_part')
            ->distinct()
            ->get();
        // dd($category,$area,$data);
        return [$data, $area, $category];
    }

    public function getAllDataProductById($id)
    {
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
                'a.originalPrice',
                'a.price'
            )->where('a.id_product', $id)
            // ->where('a.stok','>',0 )
            ->get();

        $data = [$product, $photos];

        return response()->json($data);
    }

    public function getAllDataPartById($id)
    {

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
                'a.originalPrice',
                'a.price'
            )
            ->where('a.id_product', $id)
            ->where('a.type', 'Part')
            ->get();
        // dd($product);

        // $data=[$product];
        // dd($data);

        return response()->json($product);
    }


    public function ProductManage($from)
    {
        $from != 'Part' ? $data = $this->getData('productManage', $from) : $data = $this->getAllPart();
        // dd($data);
        // dd($data[0]->id_product);
        $Route = null;
        $view = null;
        $category = null;
        $TemplateRoute = null;
        if ($from == 'Product') {
            $TemplateRoute = '/viewProduct/';
            $Route = '/addproduct';
            $view = 'ManageProduct';
            $category = null;
        } elseif ($from == 'Part') {
            $category = $this->GetPartCategory();
            $view = 'ManagePart';
            $Route = '/addPart';
            $TemplateRoute = '/viewProduct/';
        }
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        // dd(("User.Admin.".$view));
        // dd($category);
        // dd($data);
        return view(("User.Admin." . $view), ['routeForm' => $Route, 'data' => $data, 'notif' => $notifs, 'Category' => $category, 'TemplateRoute' => $TemplateRoute]);
    }

    public function GetPartCategory()
    {
        $data = DB::table('ref_category_parts')->get();
        return $data;
    }

    public function Product($wht)
    {

        $send = null;
        if ($wht != 'AllProduct') {
            $send = $wht;
        }
        $data = $this->getData($send, 'Product');
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        return view('User.Pelanggan.Product', ['data' => $data, 'wht' => $wht, 'forSearch' => $wht, 'notif' => $notifs]);
    }

    public function ViewProductAdmin($id)
    {
        $data = ($this->getDataProduct($id));
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        $header = true;
        // ,'notif'=>$notifs
        // dd($data);
        // dd($data[0]);
        // dd($data);
        if ($data[0] != null) {
            return view('/User.Admin.viewProduct', ['isNull' => 0, 'product' => $data[0][0], 'photos' => $data[1], 'notif' => $notifs, 'header' => $header]);
        }
        return view('/User.Admin.viewProduct', ['isNull' => 1]);
    }
    // Route::get('/Detil-Product', function(){
    //     return view('/ProductDetil');
    // });
    // Route::get('/Detil-Product/{id}',[ProductsController::class,'DetilProducts']);

    public function DetilProducts($idProduct)
    {
        $data = ($this->getDataProduct($idProduct));
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        // dd($data[0]);
        // dd($data);
        if ($data[0] != null) {
            return view('/User.Pelanggan.ProductDetil', ['isNull' => 0, 'product' => $data[0][0], 'photos' => $data[1], 'notif' => $notifs]);
        }
        return view('/User.Pelanggan.ProductDetil', ['isNull' => 1]);
    }

    // public function CheckoutProduct($qty, $idProduct){
    //     $old = Products::where('id_product', $idProduct)->first();
    //     // dd($old->stok);
    //     // dd(gettype($qty));
    //     $old->stok =($old->stok-$qty);
    //     // dd($old->stok);
    //     $old->save();
    //     // dd($old->stok);
    // }
    public function deleteProduct($idProduct)
    {
        return $this->delete($idProduct, 'Product');
    }

    public function deletePart($idProduct)
    {
        return ($this->delete($idProduct, 'Part'));
    }

    public function delete($idProduct, $wht)
    {
        $product = Products::where('id_product', $idProduct)->first();
        // $product = Products::where('id_product', $idProduct)->first()->delete();
        // dd($product);
        $name = $product->nama_product;
        $product->delete();
        return redirect('/Manage/Product/' . $wht)->with('pesan', "Delete " . $name . " Successfully");
    }
    public function updateProduct(Request $req, $idProduct)
    {
        $this->update($req, $idProduct, "Product");
        return redirect('/Manage/Product/Product');
    }

    public function updatePart(Request $req, $idProduct)
    {
        // dd($req->foto1);
        $this->update($req, $idProduct, "Part");
        return redirect('/Manage/Product/Part');
    }

    public function UpdateStokMinus($idProduct, $qty)
    {
        $product = Products::where('id_product', $idProduct)->first();
        $product->stok = $product->stok - $qty;
        if ($product->save()) {
            return 'success';
        }
    }
    public function UpdateStokPlus($idProduct, $qty)
    {
        $product = Products::where('id_product', $idProduct)->first();
        $product->stok = $product->stok + $qty;
        if ($product->save()) {
            return 'success';
        }
    }

    public function TransactionUpdate($idTransaction, $PlusOrMinus)
    {
        $detilTransaction = DB::table('detail__transactions as a')
            ->join('transaksis as b', 'a.Transaksis_id', '=', 'b.id')
            ->where('b.id', $idTransaction)
            ->get();

        foreach ($detilTransaction as $product) {
            ($PlusOrMinus == 'plus') ?
                $this->UpdateStokPlus($product->id_product, $product->qty)
                : $this->UpdateStokMinus($product->id_product, $product->qty);
        }
    }

    public function update(Request $req, $idProduct, $from)
    {
        // dd($req);
        $Product = Products::where('id_product', $idProduct)->first();
        $oldMain = $Product->mainPhoto;
        // dd($oldMain);
        $Product->nama_product = $req->ProductName;
        $Product->stok = $req->stock;
        $Product->price = $req->ProductPrice;
        $Product->color = $req->ProductColor;
        $Product->detail_product = $req->Description;
        $Product->price = $req->ProductPrice;
        $Product->originalPrice = $req->originalPrice;
        $Product->weight = $req->weight;
        $Product->type = $from;
        $Product->Category = $req->product;
        $Product->save();

        $photo = new PhotosController();
        $main = null;
        for ($i = 0; $i < $req->TotalPhoto; $i++) {
            // dd('foto'.$i==$req->mainPhoto);
            // if($photo->cekExist())


            if ($from == "Product") {
                if ($req->file('foto' . $i + 1) != null) {
                    // dd('masuk');
                    $tes = $photo->store($req, $Product->id_product, $i + 1, 'Product');
                    // dd($tes);
                    // dd($tes);
                }
                if ($req->mainPhoto != 'foto0') {
                    // dd("masui");
                    $main = substr($req->mainPhoto, 4);
                    // dd($main);
                    $allPhotos = $photo->getSortPhotos($Product->id_product);
                    // dd($allPhotos);
                    for ($i = 0; $i < count($allPhotos); $i++) {
                        if ($main == $i + 1) {
                            // dd(",aasuk");
                            $Product->mainPhoto = $allPhotos[$i]->id_Photo;
                            $photo->turnMain($allPhotos[$i]->id_Photo, $idProduct);
                            $Product->save();
                        }
                    }
                }
            } else {
                if ($req->file('foto1') != null) {
                    // dd('masuk');
                    $photo = Photos::where('id_product', $idProduct)->first();
                    $name = pathinfo($req->file('foto1')->store('images', 'public'), PATHINFO_BASENAME);
                    if (!$photo) {
                        $photo = new Photos();
                        $photo->id_product = $idProduct;
                        $photo->isMain = 1;
                        $photo->PhotosName = $name;
                    } else {
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

    public function ContentOn($idProduct)
    {
        return $this->ChangeContent($idProduct, 'on');
    }
    public function ContentOff($idProduct)
    {
        return $this->ChangeContent($idProduct, 'off');
    }

    public function ChangeContent($idProduct, $wht)
    {
        $data = null;
        ($wht == 'on') ? $data = 1 : $data = 0;
        DB::table('products')
            ->where('id_Product', $idProduct)
            ->update(['isContent' => $data]);

        return response()->json(['message' => 'success']);
    }

    public function LandingPage()
    {
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        if (session('Role') != 'Admin') {
            $Contens = DB::table('products as a')
                ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
                ->where('a.isContent', 1)
                ->select('a.*', 'b.*') // Pilih kolom sesuai kebutuhan
                ->get();

            $Special = $this->refresh();
            // ,'notif'=>$notifs
            if (session('isActive') != 'nonActive') {
                return view('landingpage', ['Content' => $Contens, 'Special' => $Special, 'notif' => $notifs]);
            } else {
                return redirect('/BannedAccount');
            }
        } else {

            $TotalEarning = [
                [
                    DB::table('detail__transactions as a')
                        ->join('products as b', 'b.id_product', '=', 'a.id_product')
                        ->join('transaksis as c', 'a.Transaksis_id', '=', 'c.id')
                        ->selectRaw('sum((a.qty * b.price) - (a.qty * b.originalPrice)) as laba')
                        ->where('c.Status_Transaksi', 'Acceptted')
                        ->whereDay('c.created_at', now()->day)
                        ->whereMonth('c.created_at', now()->month)
                        ->whereYear('c.created_at', now()->year)
                        ->first()
                ],
                [
                    DB::table('detail__transactions as a')
                        ->join('products as b', 'b.id_product', '=', 'a.id_product')
                        ->join('transaksis as c', 'a.Transaksis_id', '=', 'c.id')
                        ->selectRaw('sum((a.qty * b.price) - (a.qty * b.originalPrice)) as laba')
                        ->where('c.Status_Transaksi', 'Acceptted')
                        ->whereMonth('c.created_at', now()->month)
                        ->first()
                ],
                [
                    DB::table('detail__transactions as a')
                        ->join('products as b', 'b.id_product', '=', 'a.id_product')
                        ->join('transaksis as c', 'a.Transaksis_id', '=', 'c.id')
                        ->where('c.Status_Transaksi', 'Acceptted')
                        ->selectRaw('sum((a.qty * b.price) - (a.qty * b.originalPrice)) as laba')
                        ->first()

                ]
            ];

            $TotalOrder = [
                [
                    DB::table('transaksis as a')
                        ->where('a.Status_Transaksi', 'Acceptted')
                        ->whereDay('a.created_at', now()->day)
                        ->whereMonth('a.created_at', now()->month)
                        ->whereYear('a.created_at', now()->year)
                        ->sum('a.TotalShopping')
                ],
                [
                    DB::table('transaksis as a')
                        ->where('a.Status_Transaksi', 'Acceptted')
                        ->whereMonth('a.created_at', now()->month)
                        ->sum('a.TotalShopping')

                ],
                [
                    DB::table('transaksis as a')
                        ->where('a.Status_Transaksi', 'Acceptted')
                        ->sum('a.TotalShopping')
                ]
            ];

            $TotalTransaksi = DB::table('transaksis as a')
                ->where('a.Status_Transaksi', 'Acceptted')
                ->count();

            $TotalItem = [
                [
                    DB::table('transaksis as a')
                        ->join('detail__transactions as b', 'a.id', '=', 'b.Transaksis_id')
                        ->where('a.Status_Transaksi', 'Acceptted')
                        ->sum('b.qty')
                ],
                [
                    DB::table('transaksis as a')
                        ->join('detail__transactions as b', 'a.id', '=', 'b.Transaksis_id')
                        ->where('a.Status_Transaksi', 'Acceptted')
                        ->where('a.type_transaction', 'Product')
                        ->sum('b.qty')
                ],
                [
                    DB::table('transaksis')
                        ->where('type_transaction', 'Custom')
                        ->count()
                ],
                [
                    DB::table('transaksis as a')
                        ->join('detail__transactions as b', 'a.id', '=', 'b.Transaksis_id')
                        ->where('a.Status_Transaksi', 'Acceptted')
                        ->where('a.type_transaction', 'Custom')
                        ->sum('b.qty')
                ]
            ];

            $Merge = [$TotalEarning, $TotalOrder, $TotalItem, $TotalTransaksi];
            return view('User.Admin.AdminDashboard', ['notif' => $notifs, 'Data' => $Merge]);
        }
    }
    public function refresh()
    {
        // DB::table('products')->update(['isSpecial' => null]);


        // $news = $this->getDataNew();
        // foreach ($news as $item) {
        //     $this->setNew($item->id_product);
        // }

        return $this->getDataRefresh();
    }

    public function getDataRefresh()
    {
        $products = DB::table('products as a')
            ->join('photos as b', 'b.id_Photo', '=', 'a.mainPhoto')
            ->whereNotNull('a.isSpecial')
            ->select('a.*', 'b.*')
            ->limit(20)
            ->get();
        // dd($products);
        return $products;
    }
    public function getDataNew()
    {
        $TopNew = $products = DB::table('products as a')
            ->orderBy('a.created_at', 'desc')
            ->limit(3)
            ->get();
        return $TopNew;
    }

    public function setNew($idProduct)
    {
        $product = Products::where('id_product', $idProduct)->first();
        $product->isSpecial = 'NEW';
        $product->save();
    }

    public function search(Request $req, $wht)
    {
        // dd($req);
        $send = $wht;
        // dd($wht);
        $data = $this->getData($send, 'Product');
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        return view('User.Pelanggan.Product', ['data' => $data, 'wht' => $wht, 'search' => $req->search, 'forSearch' => $wht, 'notif' => $notifs]);
    }

    public function Custom($wht)
    {
        if (session('user_id') > 0) {
            $Parts = DB::table('products as a')
                ->join('photos as e', 'a.mainPhoto', '=', 'e.id_Photo')
                ->join('category_parts as c', 'c.id_part', '=', 'a.id_product')
                ->join('ref_category_parts as d', 'd.id_category_part', '=', 'c.id_category_part')
                ->where('d.Types', $wht)
                ->select('a.*', 'd.Area', 'd.Category as category_name', 'd.Types', 'c.*','d.*','e.*')
                ->get();

            // DB::table('products as a')
            //     ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            //     ->join('category_parts as c', 'c.id_part', '=', 'a.id_product')
            //     ->join('ref_category_parts as d', 'd.id_category_part', '=', 'c.id_category_part')
            //     ->where('a.type', 'Part')
            //     ->select('a.*', 'b.*', 'c.*', 'd.*')
            //     ->get();
            // dd($Parts);

            // DB::table('products as a')
            // ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            // ->where('a.type', 'Part')
            // ->select('a.*', 'b.*')
            // ->get();

            $area = DB::table('ref_category_parts')
                ->select('Area')
                ->distinct()
                ->where('Types', $wht)
                ->get();

            $category = DB::table('ref_category_parts')
                ->select('*')
                ->distinct()
                ->where('Types', $wht)
                ->get();
                // dd($category);
            // $Parts = Products::where('type', 'Part')->get();
            // dd('Parts',$Parts,'Areas',$area,'Categorys',$category);

            return view('User.Pelanggan.Custom-' . $wht, ['Parts' => $Parts, 'Areas' => $area, 'Categorys' => $category, 'active' => $wht]);
        } else {
            return view('OutOfPages');
        }
        // if(session('user_id')>0){
        // }
        // else{
        //     return view('OutOfPages');
        // }
    }

    public function ListPart()
    {
        // $data = $this->getData($send, 'Product');
        // $data = $this->getAllPart($send, 'Product');
        $data = DB::table('products as a')
            ->join('photos as b', 'a.mainPhoto', '=', 'b.id_Photo')
            ->join('category_parts as c', 'c.id_part', '=', 'a.id_product')
            ->join('ref_category_parts as d', 'd.id_category_part', '=', 'c.id_category_part')
            ->where('a.type', 'Part')
            ->select('a.*', 'b.*', 'c.*', 'd.*')
            ->get();

        // dd($data);
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        return view('User.Pelanggan.listPart', ['data' => $data, 'forSearch' => 'Part', 'notif' => $notifs]);
    }
}
