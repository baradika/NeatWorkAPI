<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        return response()->json(Pemesanan::query()->get());
    }

    public function show(string $id)
    {
        $item = Pemesanan::query()->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pelanggan' => 'required|integer|exists:users,id_user',
            'id_petugas' => 'required|integer|exists:users,id_user',
            'id_jadwal' => 'required|integer|exists:jadwal_petugas,id_jadwal',
            'lokasi' => 'required|string',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:menunggu,dikonfirmasi,selesai,dibatalkan',
        ]);
        $item = Pemesanan::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, string $id)
    {
        $item = Pemesanan::query()->findOrFail($id);
        $data = $request->validate([
            'id_pelanggan' => 'sometimes|required|integer|exists:users,id_user',
            'id_petugas' => 'sometimes|required|integer|exists:users,id_user',
            'id_jadwal' => 'sometimes|required|integer|exists:jadwal_petugas,id_jadwal',
            'lokasi' => 'sometimes|required|string',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:menunggu,dikonfirmasi,selesai,dibatalkan',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function destroy(string $id)
    {
        $item = Pemesanan::query()->findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}
