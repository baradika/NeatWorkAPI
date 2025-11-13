<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PetugasProfile;

class PetugasProfileApproved extends Mailable
{
    use Queueable, SerializesModels;

    public PetugasProfile $profile;

    public function __construct(PetugasProfile $profile)
    {
        $this->profile = $profile;
    }

    public function build()
    {
        $logoCid = null;
        try {
            $path = public_path('img/neatworklogo.png');
            if (file_exists($path)) {
                $logoCid = $this->embed($path);
            }
        } catch (\Throwable $e) {
            // ignore embed errors, fallback to text branding in template
        }

        return $this->subject('Persetujuan Verifikasi Petugas - NeatWork')
            ->view('emails.petugas_profile_approved')
            ->with([
                'fullName' => $this->profile->full_name,
                'date' => now()->format('d M Y, H:i'),
                'logoCid' => $logoCid,
            ]);
    }
}
