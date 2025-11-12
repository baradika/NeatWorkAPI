<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetugasProfile extends Model
{
    protected $table = 'petugas_profiles';
    protected $primaryKey = 'id_petugas_profile';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'ktp_number',
        'ktp_photo_path',
        'selfie_with_ktp_path',
        'full_name',
        'date_of_birth',
        'phone_number',
        'address',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
