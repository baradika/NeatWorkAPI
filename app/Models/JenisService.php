<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisService extends Model
{
    use HasFactory;

    protected $table = 'jenis_services';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_service',
        'nama_service',
        'deskripsi',
        'image_url',
        'harga',
        'estimasi_waktu'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'estimasi_waktu' => 'integer'
    ];
}
