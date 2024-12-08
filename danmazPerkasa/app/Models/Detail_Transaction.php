<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Transaction extends Model
{
    protected $primaryKey = 'id_Detail_transaction';
    use HasFactory;
    protected $fillable = [
        'id_Detail_transaction',
        'qty',
        'id_product',
        'Total',
        'Status',
    ];
}
