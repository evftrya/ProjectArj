<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\address;
use Illuminate\Http\Request;
use stdClass;

class AddressController extends Controller
{
    //
    public function store(Request $req,$detil){
        $data=null;
        $data = Address::where('id_user', session('user_id'))->first();
        // dd($email);
        if($data==null){
                $data = new address();
        }
        // dd($data);
        // if($wht=='new'){

        // }
        // else{
        //     // $data=
        // }

        $data->Provinsi = $req->provinsi;
        $data->KotaKabupaten = $req->KotaKabupaten;
        $data->Kecamatan = $req->Kecamatan;
        $data->Kelurahan = $req->Kelurahan;
        $data->RT = $req->RT;
        $data->RW = $req->RW;
        $data->KodePos = $req->KodePos;
        $data->AlamatDetil = $req->AlamatDetail;
        $data->Detil = $detil;
        $data->id_user = session('user_id');

        $cont = new Controller();
        $data->ShippingRate = $cont->getOngkir($data->KotaKabupaten);
        $data->save();        
    }

    public function getDataById(){
        $data = Address::where('id_user', session('user_id'))->first();
        if($data!=null){

            $data = DB::table('addresses as a')
                ->join('provinces as b', 'b.province_id', '=', 'a.Provinsi')
                ->join('cities as c', 'c.city_id', '=', 'a.KotaKabupaten')
                ->where('a.id_user', session('user_id'))
                ->select('a.*', 'b.*', 'c.*')
                ->get();
            $back = $data[0];
            // dd($data[0]);
        }
        else{
            $data = [];
            $data[0] = new stdClass(); // Pastikan elemen pertama adalah objek
            $data[0]->Detil = 'Alamat Belum Diisi';
            $data[0]->AlamatDetil = null;

        }
        // dd($data);
        return $data;   
    
    }

    public function isNew($idProduct){
            $data = Address::where('id_user', session('user_id'))->first();
            
            //checkStok
            $Cont = new ProductsController();
            $product = null;
            if($idProduct!='Address' && $idProduct!='CekAddress'){
    
                $product = $Cont->getDataProduct($idProduct)[0][0];
            }
    
            if($data!=null||$idProduct=='Address'){
                return response()->json(1);
            }            
            else if($idProduct=='CekAddress'){
                return response()->json(0);
            }
            else if($product->stok==0){
                if($idProduct!='Address'){
                    return response()->json(2);
                }
            }
            else{
                return response()->json(0);
    
            }
        
    }

    public function getCitiesName($id){
        $data = DB::table('cities as c')
        ->where('c.city_id', $id)
        ->select('c.city_name')
        ->first();
        // dd($data->city_name);
        return $data->city_name;
    }
    public function getProvinceName($id){
        $data =  DB::table('provinces as c')
        ->where('c.province_id', $id)
        ->select('c.province_name')
        ->first();
        return $data->province_name;
    }

    public function getDetil($idUser){
        $detil = Address::where('id_User', $idUser)->first();
        // dd($detil);
        return $detil->Detil;
    }

    public function getKecamatan($idKota){
        $kecamatans = DB::table('kecamatans')
        ->where('city_id', $idKota)
        ->orderBy('nama_kecamatan')
        ->get();

        return response()->json($kecamatans);
    }

}
