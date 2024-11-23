<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function AuthSystem(){
        if(session('user_id')==1){
            return 1;
        }
        else{
            return 0;
        }
    }
    

    public function GetUrl(){
        
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $request_uri = $_SERVER['REQUEST_URI'];
    
        // Gabungkan menjadi URL penuh
        $current_url = $protocol . '://' . $host . $request_uri;
        
        $urlName = basename($current_url);
        // Menampilkan URL saat ini
        return $urlName;
    }
}
