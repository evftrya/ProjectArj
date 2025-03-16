<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $primaryKey = 'id';
    use HasFactory;
    protected $fillable = [
        'id',
        'id_user',
        'Total',
        'Shipping',
        'PaymentMethod',
        'Notes',
        'Kode_Pembayaran',
    ];
}
