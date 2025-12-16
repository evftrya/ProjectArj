<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_product extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_category_product';


    protected $fillable = [
        'id_category_product',
        'id_product',
        'category_name',
    ];
}
