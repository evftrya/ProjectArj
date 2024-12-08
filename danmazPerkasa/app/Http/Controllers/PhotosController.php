<?php

namespace App\Http\Controllers;
// use app\Models\Photos;
use App\Models\Photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    //

    public function store(Request $req, $idProduct, $number){
        // dd($req);
        $photo = new Photos();
        // $photo->PhotosName = $req->file('foto'.$number)->getClientOriginalName().$idProduct;
        $photo->PhotosName = pathinfo($req->file('foto'.$number)->store('images','public'), PATHINFO_BASENAME);
        $photo->id_product = $idProduct;
        if($req->mainPhoto=='foto'.$number){
            $photo->isMain = 1;
        }
        $photo->save();
        
        if($req->mainPhoto=='foto'.$number){
            return $photo->id_photo;
        }
    }
}
