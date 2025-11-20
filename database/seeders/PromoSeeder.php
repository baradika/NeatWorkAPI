<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Cukup Klik Klik Klik',
                'subtitle' => 'Rumah Rapi & Resik',
                'discount' => '15%','code' => 'MIDWEEKDEALNOV',
                'period' => 'Periode: 10 - 24 November 2025',
                'emoji' => '🏠✨',
            ],
            [
                'title' => 'Promo Akhir Tahun',
                'subtitle' => 'Bersih Total!',
                'discount' => '20%','code' => 'NEWYEAR2025',
                'period' => 'Periode: 1 - 31 Desember 2025',
                'emoji' => '🎉🎊',
            ],
            [
                'title' => 'Weekend Special',
                'subtitle' => 'Santai, Rumah Bersih',
                'discount' => '10%','code' => 'WEEKEND2025',
                'period' => 'Setiap Sabtu & Minggu',
                'emoji' => '🌟💫',
            ],
        ];
        foreach ($items as $it) {
            Promo::firstOrCreate(['code' => $it['code']], $it);
        }
    }
}
