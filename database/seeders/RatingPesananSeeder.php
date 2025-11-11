<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pemesanan;
use App\Models\RatingPesanan;

class RatingPesananSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create the pelanggan
        $pelanggan = User::where('email', 'siti@cleaning.com')->first();
        
        if (!$pelanggan) {
            $this->command->info('Pelanggan not found. Please run PemesananSeeder first.');
            return;
        }

        // Get an existing pemesanan
        $pemesanan = Pemesanan::first();
        
        if (!$pemesanan) {
            $this->command->info('No pemesanan found. Please run PemesananSeeder first.');
            return;
        }

        // Check if rating already exists
        $existingRating = RatingPesanan::where('id_pemesanan', $pemesanan->id)->first();
        
        if ($existingRating) {
            $this->command->info('Rating already exists for this pemesanan.');
            return;
        }

        // Create sample rating
        RatingPesanan::create([
            'id_pemesanan' => $pemesanan->id,
            'rating' => 5,
            'ulasan' => 'Pelayanan sangat memuaskan, petugas ramah dan pekerjaan rapi!',
        ]);

        $this->command->info('Sample rating created successfully!');
    }
}
