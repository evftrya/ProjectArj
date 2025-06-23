<?php

namespace App\Http\Controllers;
// use app\Models\Photos;
use App\Models\Photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    //

    public function store(Request $req, $idProduct, $number,$wht){
        // dd($req)->file('foto3');
        // dd($wht);
        $photo = new Photos();
        // $photo->PhotosName = $req->file('foto'.$number)->getClientOriginalName().$idProduct;
        
        $photo->PhotosName = pathinfo($req->file('foto'.$number)->store('images','public'), PATHINFO_BASENAME);
        $photo->id_product = $idProduct;
        // if($wht!='Part'){
        if($wht!='Part'){
            if($req->mainPhoto=='foto'.$number){
                $photo->isMain = 1;
            }
            $photo->save();
            
            if($req->mainPhoto=='foto'.$number){
                return $photo->id_photo;
            }
        }
        else{
            $photo->isMain = 1;
            $photo->save();
            return $photo->id_photo;
        }
        // }
    }

    public function getAllProduct($id){
        $photos = DB::table('photos')
        ->select(
            'a.id_Photo',
            'a.PhotosName',
            'isMain'
        )->where('a.id_product', $id)
        ->get();

        return $photos;
    }
    // public function cekExist($PhotosName){
    //     $exist = Photos::where('PhotosName', $PhotosName)->first();
    //     $back = false;
    //     if($exist){
    //         $back=true;
    //     }
    //     return $back;
    // }

    public function getSortPhotos($idProduct){
        $photos = DB::table('photos as a')
        ->select(
            'a.id_Photo',
            'a.PhotosName',
            'a.isMain'
        )
        ->where('a.id_product', $idProduct)
        ->orderBy('a.id_Photo', 'asc')
        ->get();

        return $photos;
    }

    public function turnMain($idPhoto,$idProduct){
        DB::table('photos')
        ->where('id_Product', $idProduct)
        ->update(['isMain' => 0]);

        
        // $Photos = Photos::where('id_Photo', $idPhoto)->first();
        // $Photos->isMain = 1;
        // $Photos->save();

        DB::table('photos')
            ->where('id_Product', $idProduct)
            ->where('id_Photo', $idPhoto)
            ->update(['isMain' => 1]);

        // dd($Photos);
        
    }
}
