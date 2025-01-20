<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store($type,$id,$idUser){
        $idx = $type-1;
        $notif = new notification();
        $tipe= [['Product','/Detil-Product/',1,'Hi, We Have New Product','Come take a look it will make you amaze'], ['Transaction','',0,'',''], ['Progress','',0,'',''], ['Address','/Profile/Address',0,'Dont Forget to Fill Your Profile','Your Address is important for making some transaction']];
        // dd($notif);
        $notif->type=$tipe[$idx][0];
        $notif->link= ($tipe[$idx][2]==1)? $tipe[$idx][1].$id:$tipe[$idx][1];
        $notif->Icon=$idx;
        $notif->Title=$tipe[$idx][3];
        $notif->Detil=$tipe[$idx][4];
        $notif->isRead=0;
        $notif->id_user=$idUser;
        // dd($notif);

        $notif->save();
    }

    public function getAllNotif(){
        $data = DB::table('notifications as a')
        ->where('a.id_User', session('user_id'))
        ->select('*')
        ->get();

        return $data;
    }
}
