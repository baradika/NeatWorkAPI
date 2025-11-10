<?php

namespace Database\Seeders;

use App\Models\JenisService;
use Illuminate\Database\Seeder;

class JenisServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'kode_service' => 'S-ART',
                'nama_service' => 'Asisten Rumah Tangga',
                'deskripsi' => 'Membantu pekerjaan rumah tangga harian',
                'harga' => 50000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-DC',
                'nama_service' => 'Deep Cleaning',
                'deskripsi' => 'Pembersihan menyeluruh ruangan',
                'harga' => 75000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-LN',
                'nama_service' => 'Laundry',
                'deskripsi' => 'Jasa laundry dan setrika pakaian (harga per kg)',
                'harga' => 40000,  // Harga per kg
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-TK',
                'nama_service' => 'Tukang Kebun',
                'deskripsi' => 'Perawatan dan perbaikan taman',
                'harga' => 60000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-BB',
                'nama_service' => 'Baby Sitter',
                'deskripsi' => 'Pengasuhan dan perawatan anak',
                'harga' => 80000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ]
        ];

        foreach ($services as $service) {
            JenisService::create($service);
        }
    }
}
