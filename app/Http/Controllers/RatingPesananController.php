<?php

namespace App\Http\Controllers;

use App\Models\RatingPesanan;
use Illuminate\Http\Request;

class RatingPesananController extends Controller
{
    public function index()
    {
        return response()->json(RatingPesanan::query()->get());
    }

    public function show(string $id)
    {
        $item = RatingPesanan::query()->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pemesanan' => 'required|integer|exists:pemesanan,id_pemesanan',
            'rating' => 'nullable|integer|min:1|max:5',
            'ulasan' => 'nullable|string',
        ]);
        $item = RatingPesanan::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, string $id)
    {
        $item = RatingPesanan::query()->findOrFail($id);
        $data = $request->validate([
            'id_pemesanan' => 'sometimes|required|integer|exists:pemesanan,id_pemesanan',
            'rating' => 'nullable|integer|min:1|max:5',
            'ulasan' => 'nullable|string',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function destroy(string $id)
    {
        $item = RatingPesanan::query()->findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}
