<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $primaryKey = 'id_User'; // Pastikan ini sesuai dengan kolom primary key di tabel
    public $timestamps = true; // Pastikan kolom created_at dan updated_at ada di database
    
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

    // Fungsi untuk memeriksa role pengguna
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Fungsi untuk mengambil semua pengguna (contoh implementasi index)
    public function index()
    {
        return self::all(); // Mengambil semua data pengguna
    }
}
