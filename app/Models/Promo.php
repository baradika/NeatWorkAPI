<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promos';

    protected $fillable = [
        'title',
        'subtitle',
        'discount',
        'code',
        'period',
        'image_url',
        'emoji',
    ];
}
