<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PetugasProfile;

class PetugasProfileRejected extends Mailable
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
            // ignore embed errors
        }

        return $this->subject('Pembaruan Verifikasi Petugas - NeatWork')
            ->view('emails.petugas_profile_rejected')
            ->with([
                'fullName' => $this->profile->full_name,
                'reason' => $this->profile->rejection_reason,
                'date' => now()->format('d M Y, H:i'),
                'logoCid' => $logoCid,
            ]);
    }
}
