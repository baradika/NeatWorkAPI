<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\JenisService;
use App\Http\Requests\StorePemesananRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            $perPage = (int) request('per_page', 15);
            $pemesanans = Pemesanan::with('jenisService')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate($perPage);

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

    // Store method is already defined above with StorePemesananRequest

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

    public function cancel(string $id): JsonResponse
    {
        try {
            $pemesanan = Pemesanan::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$pemesanan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            if ($pemesanan->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pesanan tidak dapat dibatalkan'
                ], 400);
            }

            $pemesanan->status = 'cancelled';
            $pemesanan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibatalkan',
                'data' => [
                    'id' => $pemesanan->id,
                    'status' => $pemesanan->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membatalkan pemesanan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
