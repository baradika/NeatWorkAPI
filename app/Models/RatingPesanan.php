<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingPesanan extends Model
{
    use HasFactory;

    protected $table = 'rating_pesanan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_pemesanan',
        'rating',
        'ulasan',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }
}
