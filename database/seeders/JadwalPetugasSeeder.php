<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JadwalPetugas;

class JadwalPetugasSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::firstOrCreate(
            ['email' => 'budi@cleaning.com'],
            [
                'nama' => 'Budi Petugas',
                'password' => bcrypt('budi123'),
                'role' => 'petugas',
                'created_at' => now(),
            ]
        );

        JadwalPetugas::updateOrCreate(
            ['id_jadwal' => 1],
            [
                'id_petugas' => $petugas->id_user,
                'tanggal' => '2025-10-15',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '12:00:00',
                'status' => 'tersedia',
            ]
        );

        JadwalPetugas::updateOrCreate(
            ['id_jadwal' => 2],
            [
                'id_petugas' => $petugas->id_user,
                'tanggal' => '2025-10-16',
                'waktu_mulai' => '13:00:00',
                'waktu_selesai' => '17:00:00',
                'status' => 'tersedia',
            ]
        );
    }
}
