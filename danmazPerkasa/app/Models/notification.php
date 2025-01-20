<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    protected $primaryKey = 'idNotification';
    use HasFactory;
    protected $fillable = [
        'type',
        'link',
        'Icon',
        'Title',
        'Detil',
        'isRead',
    ];
}
