<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JadwalPetugas;
use App\Models\Pemesanan;
use App\Models\RatingPesanan;

class RatingPesananSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = User::firstOrCreate(
            ['email' => 'siti@cleaning.com'],
            [
                'nama' => 'Siti Pelanggan',
                'password' => 'siti123',
                'role' => 'pelanggan',
                'no_hp' => '081234567892',
                'alamat' => 'Jakarta Barat',
            ]
        );

        $petugas = User::firstOrCreate(
            ['email' => 'budi@cleaning.com'],
            [
                'nama' => 'Budi Petugas',
                'password' => 'budi123',
                'role' => 'petugas',
                'no_hp' => '081234567891',
                'alamat' => 'Jakarta Timur',
            ]
        );

        $jadwal = JadwalPetugas::first();
        if (!$jadwal) {
            $jadwal = JadwalPetugas::create([
                'id_petugas' => $petugas->id_user,
                'tanggal' => '2025-10-16',
                'waktu_mulai' => '13:00:00',
                'waktu_selesai' => '17:00:00',
                'status' => 'tersedia',
            ]);
        }

        $pemesanan = Pemesanan::firstOrCreate(
            [
                'id_pelanggan' => $pelanggan->id_user,
                'id_petugas' => $petugas->id_user,
                'id_jadwal' => $jadwal->id_jadwal,
            ],
            [
                'lokasi' => 'Jakarta',
                'catatan' => 'Order percobaan',
                'status' => 'menunggu',
            ]
        );

        RatingPesanan::updateOrCreate(
            ['id_rating' => 1],
            [
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'rating' => 5,
                'ulasan' => 'Sangat memuaskan',
            ]
        );
    }
}
