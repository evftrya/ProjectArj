<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\NotificationController;
use App\Models\address;
// namespace App\Http\Controllers\;
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
            session(['Role' => $email->role]);
            // dd(session('Role'));
            // dd(session('user_name'));
            return redirect('/Login');
        }
        else{
            return redirect('/Login')->with('pesan', "Registration Succesfull");
        }
    }


    public function cekLogin(Request $req,$wht){
        // dd($req);
        if($wht=='Login'){
            return ($this->cekExistEmail($req->el, $req->pu,$wht));
        }
        else{
            return ($this->cekExistEmail($req->el, null , $wht));
        }
    }
    public function cekExistEmail($email, $password, $wht){
        $data = User::where('emailUser', $email)->first();
        $email = null;
        $pw = null;
        // dd($data);
        if($data){
            // return response()->json(['message'=> 'ada']);
            $hasil = $email;
            if($wht=="Login"){
                if($data->passwordUser!=$password || $data->passwordUser==null){
                    $pw = 'Wrong Password';
                    $hasil = $pw;
                }
                // else if()
                else{
                    $hasil = 'Good';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                }
            }
            else{
                $hasil = 'Email have been Exist try another email...';
            }
        }
        else{
            $email = 'Email Not Registered';
            $hasil = $email;
        }
        
        
        // dd($hasil);
            // return response()->json(['message'=> 'success']);
            // return $hasil;
        return response()->json(['message'=> $hasil]);
            // return response()->json(['message'=> 'tes']);

    }

    public function getProfile($idUser){
        $data = User::where('id_User', $idUser)->first();
        // dd("data".$data);
        return $data;
    }

    public function Logout(){
        session_abort();
        Auth::logout();
        session(['user_id' => 0]);
        session(['direction'=>null]);
        session(['Role'=>null]);
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

                $notif = new NotificationController();
                $notif->store(4,0,$user->id_User);

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
        $Allert = null;
        // dd($akun);
        if($wht=="Info"){
            // dd(($req->firstName && $req->lastName)."f=".$req->firstName."|". $req->lastName);
            ($req->firstName && $req->lastName) ? 
            $akun->namaUser = ($req->firstName." ".$req->lastName) :
            null;
            ($req->emailUser) ? $akun->emailUser = $req->emailUser: null;
            ($req->Phone) ? $akun->Phone = $req->Phone : null;
            ($req->Gender) ? $akun->Gender = $req->Gender: null;
            $Allert = "Info change succesfull";
            // dd($akun);
            // dd($wht);
        }
        else if($wht=="ChangePassword"){
            // dd($req->currentPassword===$akun->passwordUser);
            if($req->currentPassword!=null){
                if($req->currentPassword===$akun->passwordUser){
                    if($req->NewPassword==$req->RetypeNewPassword){
                        $akun->passwordUser = $req->NewPassword;
                    }
                    $Allert = "Password change succesfull1";
                }
                else{
                    $Allert = "The old password is not the same as the previous password0";
                    
                }
            }
            
            $wht = "Change-Password";


        }
        else if($wht=="Address"){
            $addrs = new AddressController();
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
                    ($key=="provinsi") ? $value="Prov. ".$addrs->getProvinceName(intval($value)) : null;
                    // ($key=="provinsi") ? dd(intval($value)) : null;
                    ($key=="KotaKabupaten") ? $value=$addrs->getCitiesName(intval($value)) : null;
                    // ($key == "KotaKabupaten") ? dd(gettype($addrs->getCitiesName(intval($value)))) : null;

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
            // dd($urutanAddress);     
            $address = implode(", ", $urutanAddress).", Indonesia";
            // dd($address);
            $conAdd = new AddressController();
            $conAdd->store($req,$address);
            //$akun->Address = $address;
            // dd($address);
            $Allert = "Address change succesfull1";

        }
        $akun->Save();
        session(['user_name' => $akun->namaUser]);
        // dd('/Profile/'.$wht);
        return redirect('/Profile/'.$wht)->with('message', $Allert);

    }
    public function getAllData(){
        $data = DB::table('users as a')
        ->leftJoin('addresses as b', 'a.id_User', '=', 'b.id_user')
        ->select(
            'a.id_User as id',
            'a.namaUser as name',
            'a.emailUser as email',
            'a.passwordUser as pw',
            'a.role',
            'a.Phone',
            'a.Gender',
            'b.Detil as address'
        )
        ->where('a.id_User', '!=', 1)
        ->get();
    
    

        return $data;
    }

    public function manageUser(){
        $data = $this->getAllData();
        $notif = new NotificationController();
        $notifs = $notif->getAllNotif();
        // ,'notif'=>$notifs
        // dd($data);
        return view('User.Admin.ManageUser',['data' => $data, 'whtRoute' => 'Manage User','notif'=>$notifs]);
    }

    public function ChangePassword(Request $req){
        // \Log::info('Request received:', ['request' => $req->all()]);
        dd($req);
    }

    
}
