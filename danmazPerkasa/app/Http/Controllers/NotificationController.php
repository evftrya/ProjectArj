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
        $tipe = [
            [
                'type' => 'Product',
                'link' => '/Detil-Product/',
                'status' => 1,
                'title' => 'Hi, We Have New Product',
                'subtitle' => 'Come take a look it will make you amaze'
            ],
            [
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/',
                'status' => 2,
                'title' => 'Transaction Successful',
                'subtitle' => 'Your transaction was successful. Thank you!'
            ],
            [
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/',
                'status' => 3,
                'title' => 'Transaction Passed Deadline',
                'subtitle' => "This transaction has passed the deadline and can no longer be processed."
            ],
            [
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/',
                'status' => 4,
                'title' => 'Transaction Cancelled',
                'subtitle' => "Your transaction has been cancelled."
            ],
            [
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/',
                'status' => 5,
                'title' => "Thank you! Your payment was successful.",
                'subtitle' => 'Tap for detail'
            ],
            [
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/',
                'status' => 6,
                'title' => "Sorry, your payment could not be processed. Please try again or use a different payment method.",
                'subtitle' => 'Tap for detail'
            ],
            [
                'type' => 'Transaction-Admin',
                'link' => '/Transaction/',
                'status' => 7,
                'title' => "New Order!",
                'subtitle' => 'Check For Details.'
            ],
            [
                'type' => 'Progress',
                'link' => '',
                'status' => 0,
                'title' => '',
                'subtitle' => ''
            ],
            [
                'type' => 'Address',
                'link' => '/Profile/Address',
                'status' => 8,
                'title' => 'Don’t Forget to Fill Your Profile',
                'subtitle' => 'Your Address is important for making some transaction'
            ],
            [
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/',
                'status' => 9,
                'title' => 'Order Accepted',
                'subtitle' => 'please wait for the next confirmation!'
            ],
            [
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/',
                'status' => 10,
                'title' => "Sorry, your order couldn't be processed",
                'subtitle' => 'Check the Detils'
            ],
        ];
        
        
        
        // dd($notif);
        // dd($tipe[$idx]['type']);
        $notif->type=$tipe[$idx]['type'];
        $link=null;
        //Add Product
        ($tipe[$idx]['status']==1)? $link=$tipe[$idx]['link'].$id:null;
        
        //Transaction
            //Customer
            ($tipe[$idx]['status']>=2&&$tipe[$idx]['status']<=7)? $link=$tipe[$idx]['link'].$id:null;
            ($tipe[$idx]['status']>=10&&$tipe[$idx]['status']<=11)? $link=$tipe[$idx]['link'].$id:null;
            // Admin
            // ($tipe[$idx]['status']==7)? $link=$tipe[$idx]['link']:null;

        
        //Address
        ($tipe[$idx]['status']==8)? $link=$tipe[$idx]['link']:null;

        


        $notif->link=$link;
        $notif->Icon=$idx;
        $notif->Title=$tipe[$idx]['title'];
        $notif->Detil=$tipe[$idx]['subtitle'];
        $notif->isRead=0;
        $notif->id_user=$idUser;
        // dd($notif);
        // dd($notif);
        $notif->save();
    }

    public function getAllNotif(){
        $data = DB::table('notifications as a')
            ->where('a.id_User', session('user_id'))
            ->orderBy('a.created_at', 'desc')
            ->select('*')
            ->get();

        return $data;
    }
}
