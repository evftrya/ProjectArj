<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{

    protected $primaryKey = 'idAddress';
    use HasFactory;
    protected $fillable = [
        'Provinsi',
        'KotaKabupaten',
        'Kecamatan',
        'Kelurahan',
        'RT',
        'RW',
        'KodePos',
        'AlamatDetil',
        'Detil',
    ];
    use HasFactory;
}
