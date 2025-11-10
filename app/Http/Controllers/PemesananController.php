<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\JenisService;
use App\Http\Requests\StorePemesananRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemesananController extends Controller
{
    public function store(StorePemesananRequest $request): JsonResponse
    {
        try {
            // Check if service exists
            $service = JenisService::findOrFail($request->jenis_service_id);
            
            // Calculate total price based on duration
            $totalHarga = $service->harga * $request->duration;
            
            // Create new booking
            $pemesanan = Pemesanan::create([
                'user_id' => Auth::id(),
                'jenis_service_id' => $request->jenis_service_id,
                'alamat' => $request->alamat,
                'service_date' => $request->service_date,
                'duration' => $request->duration,
                'preferred_gender' => $request->preferred_gender,
                'catatan' => $request->catatan,
                'status' => 'pending',
                'total_harga' => $totalHarga,
                'tanggal_pesan' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan berhasil dibuat',
                'data' => $pemesanan->load('jenisService')
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat pemesanan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $pemesanans = Pemesanan::with('jenisService')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $pemesanans
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data pemesanan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
