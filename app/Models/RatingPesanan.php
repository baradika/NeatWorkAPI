<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingPesanan extends Model
{
    use HasFactory;

    protected $table = 'rating_pesanan';
    protected $primaryKey = 'id_rating';
    public $timestamps = false;

    protected $fillable = [
        'id_pemesanan',
        'rating',
        'ulasan',
        'created_at',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
