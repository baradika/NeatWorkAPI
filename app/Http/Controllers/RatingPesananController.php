<?php

namespace App\Http\Controllers;

use App\Models\RatingPesanan;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;
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
            'id_pemesanan' => 'required|integer|exists:pemesanans,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $order = Pemesanan::query()->findOrFail($data['id_pemesanan']);
        if ($order->user_id !== $user->id_user) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak berhak memberi rating untuk pesanan ini'], 403);
        }
        if ($order->status !== 'selesai') {
            return response()->json(['status' => 'error', 'message' => 'Rating hanya dapat diberikan setelah pesanan selesai'], 400);
        }
        $exists = RatingPesanan::query()->where('id_pemesanan', $order->id)->exists();
        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan ini sudah diberi rating'], 409);
        }

        $item = RatingPesanan::create([
            'id_pemesanan' => $order->id,
            'rating' => $data['rating'],
            'ulasan' => $data['ulasan'] ?? null,
        ]);
        return response()->json(['status' => 'success', 'data' => $item], 201);
    }

    public function update(Request $request, string $id)
    {
        $item = RatingPesanan::query()->findOrFail($id);
        $order = Pemesanan::query()->findOrFail($item->id_pemesanan);
        $user = Auth::user();
        if ($order->user_id !== $user->id_user) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak berhak mengubah rating ini'], 403);
        }
        $data = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'ulasan' => 'nullable|string',
        ]);
        $item->update($data);
        return response()->json(['status' => 'success', 'data' => $item]);
    }

    public function destroy(string $id)
    {
        $item = RatingPesanan::query()->findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }

    public function ratingStatus(string $orderId)
    {
        $user = Auth::user();
        $order = Pemesanan::query()->findOrFail($orderId);
        $existing = RatingPesanan::query()->where('id_pemesanan', $order->id)->first();
        $canRate = $order->status === 'selesai' && $order->user_id === $user->id_user && !$existing;
        return response()->json([
            'status' => 'success',
            'data' => [
                'can_rate' => $canRate,
                'existing' => $existing,
            ]
        ]);
    }
}
