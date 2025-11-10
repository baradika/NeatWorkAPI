<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';
    protected $primaryKey = 'id_pemesanan';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'jenis_service_id',
        'alamat',
        'service_date',
        'duration',
        'preferred_gender',
        'id_jadwal',
        'lokasi',
        'catatan',
        'status',
        'tanggal_pesan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan', 'id_user');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas', 'id_user');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPetugas::class, 'id_jadwal', 'id_jadwal');
    }

    public function rating()
    {
        return $this->hasOne(RatingPesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
