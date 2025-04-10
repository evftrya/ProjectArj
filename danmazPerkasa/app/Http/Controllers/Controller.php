<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function AuthSystem(){
        if(session('user_id')>0){
            return 1;
        }
        else{
            return 0;
        }
    }
    
    public function authRoute($direction){
        if($this->AuthSystem()>0){
            dd('masukif');
            return redirect($direction);
        }
        elseif($direction!="/Login"){
            // session_start();
            session(['direction' => $direction]);
            // dd(session('direction'));
            return redirect('/Login');
            // dd('masukelif');
        }
        else{
            dd('masukelse');
            return redirect('/Login');
        }
    }

    public function GetUrl(){
        
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $request_uri = $_SERVER['REQUEST_URI'];
        $current_url = $protocol . '://' . $host . $request_uri;
        
        // Memisahkan path berdasarkan "/" dan mengambil elemen kedua
        $urlParts = explode('/', ltrim($request_uri, '/'));
        $firstSegment = isset($urlParts[0]) ? $urlParts[0] : ''; // Cek apakah elemen pertama ada

        return $firstSegment;
    }

    public function Profile($wht,AccountController $acc){

        //------------------------------------------------------------------------

        if($this->AuthSystem()>0){
            // dd(session('direction'));
            if(session('direction')==null){
                
                $accInfo = $acc->getProfile(session('user_id'));


                //pisah nama depan n blakang
                $fullname = explode(" ", $accInfo->namaUser);
                // dd($fullname);
                $accInfo->firstName = trim($fullname[0]);
                $accInfo->lastName = trim($fullname[1]);
                // dd($accInfo->fisrtName."||".$accInfo->lastName);
                for($i=1;$i<=strlen($accInfo->passwordUser);$i++){
                    $lenpw = str_repeat('*',$i);
                }
                $accInfo->lenPassword = $lenpw;
                // dd($accInfo->lenPassword);

                
                $cp = $wht;
                if($wht=="Change-Password"){
                    $cp = "ChangePassword";

                }
                $datas=null;
                if($wht=='Address'){
                    // $city = $this->getCity();
                    $province = $this->getProvince();
                    $address = new AddressController();
                    $accInfo['address'] = $address->getDataById();
                    $accInfo['Province'] = $province;
                    // ,'notif'=>$notifs
                    // $city = $this->getCity();
                }
                $notif = new NotificationController();
                $notifs = $notif->getAllNotif();
                // dd($accInfo);

                return view('profile',['wht'=>$wht,'data'=>$accInfo,'cp'=>$cp,'notif'=>$notifs]);
            }
        }
        else{
            session(['direction' => '/Profile/Info']);
            return redirect('/Login');
        }



        
    }
    

    public function ProfileUpdate(Request $req,AccountController $acc,$wht){
        // dd($req);
        return $acc->update($req,$wht);
    }

    public function getCity($province){

        $cities = DB::table('cities as a')
            ->where('a.province_id', $province)
            ->get();
        
        return response()->json($cities);

        // return $response;
    }

    public function getProvince(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            $this->ApiKeyRajaOngkir()
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        
        return ($this->toJson($response)['rajaongkir']['results']);

    }

    public function CekOngkir($kurir,$tujuan){
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=154&destination=".$tujuan."&weight=1000&courier=".$kurir,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                $this->ApiKeyRajaOngkir()
                
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        
        // dd($response);
        // dd($this->toJson($response));
        
        return ($this->toJson($response)['rajaongkir']['results']);
    }
    
    public function getOngkir($tujuan){
        $kurir = ['jne','pos','tiki'];
        $data=[];
        for($i=0;$i<count($kurir);$i++){
            array_push($data,$this->CekOngkir($kurir[$i],$tujuan));
        }
        $back = [json_encode($data),$data];
        return $back;
    }
    
    public function Ongkir(){
        // dd($this->getCity());
        // $City = ($this->getCity(21));
        // $Province = ($this->getProvince());
        // dd($Province);
        // dd($this->toJson($data)['rajaongkir']['results']);
        // return view();
        // return $this->CekOngkir();getOngkir
        // return $this->getOngkir();
    }

    public function toJson($ary){
        $array = json_decode($ary, true);
        return $array;
    }

    public function ApiKeyRajaOngkir(){
        return 'key: e180111ce91e552a41ff1e7a7bbb198e';
    }
}   

