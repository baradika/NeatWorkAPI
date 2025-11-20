<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::orderByDesc('created_at')->get();
        return response()->json(['data' => $promos]);
    }

    public function show(string $id)
    {
        $promo = Promo::findOrFail($id);
        return response()->json(['data' => $promo]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:200',
            'discount' => 'nullable|string|max:50',
            'code' => 'required|string|max:100|unique:promos,code',
            'period' => 'nullable|string|max:200',
            'image_url' => 'nullable|url|max:500',
            'emoji' => 'nullable|string|max:10',
        ]);
        $promo = Promo::create($data);
        return response()->json(['data' => $promo], 201);
    }

    public function update(Request $request, string $id)
    {
        $promo = Promo::findOrFail($id);
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:200',
            'subtitle' => 'nullable|string|max:200',
            'discount' => 'nullable|string|max:50',
            'code' => 'sometimes|required|string|max:100|unique:promos,code,' . $promo->id,
            'period' => 'nullable|string|max:200',
            'image_url' => 'nullable|url|max:500',
            'emoji' => 'nullable|string|max:10',
        ]);
        $promo->update($data);
        return response()->json(['data' => $promo]);
    }

    public function destroy(string $id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return response()->json(null, 204);
    }

    public function validateCode(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'subtotal' => 'nullable|numeric|min:0',
        ]);
        $code = $data['code'];
        $promo = Promo::whereRaw('LOWER(code) = ?', [strtolower($code)])->first();
        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Kode promo tidak ditemukan'], 404);
        }
        $discountRaw = (string)($promo->discount ?? '');
        $subtotal = (float)($data['subtotal'] ?? 0);
        $discountAmount = 0.0;
        $discountType = 'amount';
        if (str_ends_with($discountRaw, '%')) {
            $pct = (float)str_replace('%', '', $discountRaw);
            if ($subtotal > 0 && $pct > 0) {
                $discountAmount = round($subtotal * ($pct / 100.0), 2);
            }
            $discountType = 'percent';
        } else {
            $val = (float)preg_replace('/[^0-9.]/', '', $discountRaw);
            if ($val > 0) $discountAmount = $val;
        }
        return response()->json([
            'valid' => true,
            'data' => [
                'code' => $promo->code,
                'title' => $promo->title,
                'discount' => $promo->discount,
                'discount_type' => $discountType,
                'discount_amount' => $discountAmount,
            ]
        ]);
    }
}

