<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisService;
use App\Models\Promo;

class PricingController extends Controller
{
    public function estimate(Request $request)
    {
        $data = $request->validate([
            'jenis_service_id' => 'required|exists:jenis_services,id',
            'duration' => 'required|integer|min:1',
            'people_count' => 'nullable|integer|min:1',
            'promo_code' => 'nullable|string',
        ]);

        $service = JenisService::findOrFail($data['jenis_service_id']);
        $duration = (int) $data['duration'];
        $people = (int) ($data['people_count'] ?? 1);

        // Match current booking logic: total = harga * duration
        // If you want to factor people_count, multiply here accordingly
        $subtotal = (float) $service->harga * $duration;

        $discountAmount = 0.0;
        $appliedPromo = null;
        if (!empty($data['promo_code'])) {
            $promo = Promo::whereRaw('LOWER(code) = ?', [strtolower($data['promo_code'])])->first();
            if ($promo) {
                $discountRaw = (string)($promo->discount ?? '');
                if (str_ends_with($discountRaw, '%')) {
                    $pct = (float)str_replace('%', '', $discountRaw);
                    if ($pct > 0) {
                        $discountAmount = round($subtotal * ($pct / 100.0), 2);
                    }
                } else {
                    $val = (float)preg_replace('/[^0-9.]/', '', $discountRaw);
                    if ($val > 0) $discountAmount = $val;
                }
                $appliedPromo = [
                    'code' => $promo->code,
                    'title' => $promo->title,
                    'discount' => $promo->discount,
                ];
            }
        }

        $total = max(0, round($subtotal - $discountAmount, 2));

        return response()->json([
            'data' => [
                'subtotal' => round($subtotal, 2),
                'discount' => round($discountAmount, 2),
                'total' => $total,
                'currency' => 'IDR',
                'applied_promo' => $appliedPromo,
            ]
        ]);
    }
}
