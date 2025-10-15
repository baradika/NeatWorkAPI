<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPetugas extends Model
{
    use HasFactory;

    protected $table = 'jadwal_petugas';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;

    protected $fillable = [
        'id_petugas',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
    ];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas', 'id_user');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_jadwal', 'id_jadwal');
    }
}
