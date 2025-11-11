<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'jenis_service_id',
        'alamat',
        'service_date',
        'duration',
        'preferred_gender',
        'status',
        'catatan',
    ];

    protected $dates = ['service_date'];
    
    protected $casts = [
        'service_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisService()
    {
        return $this->belongsTo(JenisService::class, 'jenis_service_id');
    }

    public function rating()
    {
        return $this->hasOne(RatingPesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
