<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\AccountController;
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
        // if($wht=="Info"){
        //     return view('profile',['wht'=>$wht]);
        // }
        $accInfo = $acc->getProfile(session('user_id'));
        


        //pisah nama depan n blakang
        $fullname = explode(" ", $accInfo->namaUser);
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
            $cp = "Change Password";

        }
        return view('profile',['wht'=>$wht,'data'=>$accInfo,'cp'=>$cp]);
    }
    public function Product($wht){
        


        return view ('Product');
    }

    public function ProfileUpdate(Request $req,AccountController $acc,$wht){
        // dd($req);
        $acc->update($req,$wht);
        return redirect('/Profile/'.$wht);
    }
}
