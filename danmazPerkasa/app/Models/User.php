<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $primaryKey = 'id_User';
    use HasFactory;
    protected $fillable = [
        'id_User',
        'namaUser',
        'emailUser',
        'passwordUser',
        'role',
        'last_used_At',
        'created_at',
        'updated_at',
    ];
    public function index(){

    }
}
