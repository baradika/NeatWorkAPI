<?php

namespace App\Http\Controllers;

use App\Models\JadwalPetugas;
use Illuminate\Http\Request;

class JadwalPetugasController extends Controller
{
    public function index()
    {
        return response()->json(JadwalPetugas::query()->get());
    }

    public function show(string $id)
    {
        $item = JadwalPetugas::query()->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_petugas' => 'required|integer|exists:users,id_user',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i:s',
            'waktu_selesai' => 'required|date_format:H:i:s',
            'status' => 'nullable|in:tersedia,dipesan,selesai',
        ]);
        $item = JadwalPetugas::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, string $id)
    {
        $item = JadwalPetugas::query()->findOrFail($id);
        $data = $request->validate([
            'id_petugas' => 'sometimes|required|integer|exists:users,id_user',
            'tanggal' => 'sometimes|required|date',
            'waktu_mulai' => 'sometimes|required|date_format:H:i:s',
            'waktu_selesai' => 'sometimes|required|date_format:H:i:s',
            'status' => 'nullable|in:tersedia,dipesan,selesai',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function destroy(string $id)
    {
        $item = JadwalPetugas::query()->findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}
