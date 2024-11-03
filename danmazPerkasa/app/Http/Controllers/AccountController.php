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
            session(['user_id' => 1]);
            return redirect('/Login');
        }
        else{
            return redirect('/');
        }



        // dd($request);
        // $credentials = [
        //     'emailUser'     => $request->input('emailUser'),
        //     'password'  => $request->input('passwordUser'),
        // ];
        // dd($credentials);
        // dd($credentials);
        
        
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
                return redirect('/');
            }
        }

        
    }
}
