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
                'image_url' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=640',
                'harga' => 50000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-DC',
                'nama_service' => 'Deep Cleaning',
                'deskripsi' => 'Pembersihan menyeluruh ruangan',
                'image_url' => 'https://images.unsplash.com/photo-1585421514738-01798e348b17?w=640',
                'harga' => 75000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-LN',
                'nama_service' => 'Laundry',
                'deskripsi' => 'Jasa laundry dan setrika pakaian (harga per kg)',
                'image_url' => 'https://images.unsplash.com/photo-1581579188871-45ea61f2a0c8?w=640',
                'harga' => 40000,  // Harga per kg
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-TK',
                'nama_service' => 'Tukang Kebun',
                'deskripsi' => 'Perawatan dan perbaikan taman',
                'image_url' => 'https://images.unsplash.com/photo-1523419409543-ae7e2e1f7f2f?w=640',
                'harga' => 60000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ],
            [
                'kode_service' => 'S-BB',
                'nama_service' => 'Baby Sitter',
                'deskripsi' => 'Pengasuhan dan perawatan anak',
                'image_url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=640',
                'harga' => 80000,  // Harga per jam
                'estimasi_waktu' => 1  // Waktu minimum 1 jam
            ]
        ];

        foreach ($services as $service) {
            JenisService::create($service);
        }
    }
}
