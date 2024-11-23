<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AccountController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'emailUser'         => 'required|email',
            'passwordUser'   => 'required'
        ]);
        $email = User::where('emailUser', $request->input('emailUser'))->first();
        // DD($email);

        if($email){
            session_start();
            session(['user_id' => $email->id_User]);
            session(['user_name' => $email->namaUser]);
            // dd(session('user_name'));
            return redirect('/Login');
        }
        else{
            return redirect('/Login');
        }


        
        
    }
    public function getProfile($idUser){
        $data = User::where('id_User', $idUser)->first();
        // dd("data".$data);
        return $data;
    }

    public function Logout(){
        session_abort();
        session(['user_id' => 0]);
        return redirect(('/Login'));
    }

    public function AuthUser($email, $pw){
        
    }

    public function store(Request $req){
        $val = $req->validate(([
            'firstName' => 'required',
            'lastName' => 'required',
            'emailUser' => 'required',
            'passwordUser' => 'required',
        ]));
        if($val){
            $email = User::where('emailUser', $val['emailUser'])->first();
            if(!$email){
                $namaUser = $val['firstName']." ".$val['lastName'];
                $user = new User;
                $user->namaUser = $namaUser;
                $user->emailUser = $val['emailUser'];
                $user->passwordUser = $val['passwordUser'];
                $user->role = 'User';
                $user->save();

                return redirect('/Login');
            }
            else{
                return redirect('/Register');
            }
        }

        
    }
    public function update(Request $req,$wht){
        // dd(session('user_id'));
        // dd($req);
        $akun = $this->getProfile(session('user_id'));
        // dd($akun);
        if($wht=="Info"){
            // dd(($req->firstName && $req->lastName)."f=".$req->firstName."|". $req->lastName);
            ($req->firstName && $req->lastName) ? 
            $akun->namaUser = ($req->firstName." ".$req->lastName) :
            null;
            ($req->emailUser) ? $akun->emailUser = $req->emailUser: null;
            ($req->Phone) ? $akun->Phone = $req->Phone : null;
            ($req->Gender) ? $akun->Gender = $req->Gender: null;
            // dd($akun);
            // dd($wht);
        }
        else if($wht=="ChangePassword"){
            // dd($req);
            if($req->currentPassword){
                if($req->currentPassword===$akun->passwordUser){
                    if($req->newPassword===$req->RetypeNewPassword){
                        $akun->passwordUser = $req->newPassword;
                    }
                }
            }
        }
        else if($wht=="Address"){
            // $urutanAddress = [$req->AlamatDetail, $req->];
            $urutanAddress = [];
            // $req->RTRW = "tes";
            $RTRW = null;
            if($req->RT!=null || $req->RW!=null){
                ($req->RT!=null) ? $RTRW = "RT. ".$req->RT :
                $RTRW = "RW. ".$req->RW;
            }
            if(($req->RT!=null && $req->RW!=null)){
                $RTRW = "RT. ".$req->RT."/RW. ".$req->RW ;
            };
            
            // dd($req->all());
            foreach(array_reverse($req->all()) as $key=>$value){
                
                // dd($key."|".$value);
                if($value!=null){
                    ($key=="provinsi") ? $value="Prov. ".$value : null;
                    ($key=="Kecamatan") ? $value="Kec. ".$value : null;
                    ($key=="Kelurahan") ? $value="Kel. ".$value : null;
                    
    
                    // dd($key!="RT");
                    if($key != "_token" && $key != "RW" && $key != "RT"){
                        
                        array_push($urutanAddress, $value);
                        if($RTRW!=null){
                            ($key=="AlamatDetail") ? 
                            array_push($urutanAddress, $RTRW) : null;
                        } 
                    }
                }
                
                
            }
            $address = implode(", ", $urutanAddress).", Indonesia";
            $akun->Address = $address;
            // dd($address);

        }
        $akun->Save();
        session(['user_name' => $akun->namaUser]);
    }
}
