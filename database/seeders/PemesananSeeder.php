<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JenisService;
use App\Models\Pemesanan;
use Illuminate\Database\Seeder;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a user
        $user = User::firstOrCreate(
            ['email' => 'siti@cleaning.com'],
            [
                'nama' => 'Siti Pelanggan',
                'password' => bcrypt('siti123'),
                'role' => 'pelanggan',
                'created_at' => now(),
            ]
        );

        // Get or create a service type
        $service = JenisService::first();
        if (!$service) {
            $service = JenisService::create([
                'nama' => 'Regular Cleaning',
                'deskripsi' => 'Regular cleaning service',
                'harga' => 150000,
                'durasi' => 2, // in hours
            ]);
        }

        // Create sample pemesanan
        Pemesanan::create([
            'user_id' => $user->id_user,
            'jenis_service_id' => $service->id,
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'service_date' => now()->addDays(2)->toDateString(),
            'duration' => 2,
            'preferred_gender' => 'any',
            'status' => 'pending',
            'catatan' => 'Mohon bawa perlengkapan lengkap',
        ]);
    }
}
