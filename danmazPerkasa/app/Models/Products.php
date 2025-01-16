<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Products extends Model
{
    // use HasFactory;
    protected $primaryKey = 'id_product';
    use HasFactory;
    protected $fillable = [
        'id_product',
        'nama_product',
        'stok',
        'type',
        'price',
        'isContent',
        'shortQuotes',
        'isSpecial',
        'color',
        'weight',
        'Category',
        'detail_product',
        'Features',
        'mainPhoto',
        'created_at',
        'updated_at',
    ];
}
