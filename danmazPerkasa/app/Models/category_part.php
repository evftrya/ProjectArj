<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_part extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_category_part';

    protected $fillable = [
        'id_part',
        'id_category_part',
    ];

    // protected $cast = [
    //     'updated'
    // ]
}

