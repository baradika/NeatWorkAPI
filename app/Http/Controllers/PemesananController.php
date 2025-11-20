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
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'service_date' => $request->service_date,
                'service_time' => $request->service_time,
                'duration' => $request->duration,
                'preferred_gender' => $request->preferred_gender,
                'people_count' => $request->people_count,
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

    public function availableForStaff(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $profile = $user?->petugasProfile;
            if (!$profile || $profile->status !== 'approved') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Profil petugas tidak ditemukan atau belum disetujui'
                ], 403);
            }

            $gender = $profile->gender; // 'male' | 'female'

            $query = Pemesanan::with('jenisService')
                ->whereIn('status', ['pending', 'diproses'])
                ->where(function ($q) use ($gender) {
                    $q->where('preferred_gender', 'any')
                      ->orWhere('preferred_gender', $gender);
                });

            if ($request->filled('date')) {
                $query->whereDate('service_date', $request->get('date'));
            }
            if ($request->filled('service_id')) {
                $query->where('jenis_service_id', (int) $request->get('service_id'));
            }

            $perPage = (int) $request->get('per_page', 15);
            $items = $query->latest()->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat pemesanan yang tersedia',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function acceptByStaff(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $profile = $user?->petugasProfile;
            if (!$profile || $profile->status !== 'approved') {
                return response()->json(['status' => 'error', 'message' => 'Profil petugas belum disetujui'], 403);
            }

            $p = Pemesanan::query()->with('jenisService')->findOrFail($id);
            if (!in_array($p->status, ['pending', 'diproses'])) {
                return response()->json(['status' => 'error', 'message' => 'Pemesanan tidak bisa diterima'], 400);
            }

            // Gender check: only allow if matches or any
            $staffGender = $profile->gender; // male|female
            if (!in_array($p->preferred_gender, ['any', $staffGender])) {
                return response()->json(['status' => 'error', 'message' => 'Pemesanan tidak sesuai preferensi gender'], 400);
            }

            $p->assigned_petugas_id = $user->id_user;
            $p->status = 'diproses';
            $p->save();

            return response()->json(['status' => 'success', 'data' => $p->load('jenisService')]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menerima pemesanan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function rejectByStaff(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $p = Pemesanan::query()->findOrFail($id);
            if (!in_array($p->status, ['pending', 'diproses'])) {
                return response()->json(['status' => 'error', 'message' => 'Pemesanan tidak bisa ditolak'], 400);
            }
            // If petugas already assigned and not the same person, block
            if ($p->assigned_petugas_id && $p->assigned_petugas_id !== $user->id_user) {
                return response()->json(['status' => 'error', 'message' => 'Pemesanan sudah ditangani petugas lain'], 400);
            }

            // Revert to pending without assignment, or set ditolak
            $p->assigned_petugas_id = null;
            $p->status = 'pending';
            $p->save();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak pemesanan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function myBookings(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $now = Carbon::now()->toDateString();

            $baseQuery = Pemesanan::with('jenisService')
                ->where('assigned_petugas_id', $user->id_user);

            // Incoming: tanggal >= hari ini dan status diproses/diterima
            $incoming = (clone $baseQuery)
                ->whereIn('status', ['diproses', 'diterima'])
                ->whereDate('service_date', '>=', $now)
                ->latest()->get();

            // In-progress: tanggal == hari ini dan status diproses
            $inProgress = (clone $baseQuery)
                ->where('status', 'diproses')
                ->whereDate('service_date', '=', $now)
                ->latest()->get();

            // Completed: status selesai
            $completed = (clone $baseQuery)
                ->where('status', 'selesai')
                ->latest()->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'incoming' => $incoming,
                    'in_progress' => $inProgress,
                    'completed' => $completed,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat data booking petugas',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function startByStaff(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $profile = $user?->petugasProfile;
            if (!$profile || $profile->status !== 'approved') {
                return response()->json(['status' => 'error', 'message' => 'Profil petugas belum disetujui'], 403);
            }
            $p = Pemesanan::query()->findOrFail($id);
            if ($p->assigned_petugas_id !== $user->id_user) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak ditugaskan pada pemesanan ini'], 403);
            }
            if (!in_array($p->status, ['diproses', 'diterima'])) {
                return response()->json(['status' => 'error', 'message' => 'Status pemesanan tidak valid untuk mulai'], 400);
            }
            $p->status = 'diproses';
            $p->save();
            return response()->json(['status' => 'success', 'data' => $p]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memulai pekerjaan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function completeByStaff(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $p = Pemesanan::query()->findOrFail($id);
            if ($p->assigned_petugas_id !== $user->id_user) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak ditugaskan pada pemesanan ini'], 403);
            }
            if ($p->status !== 'diproses') {
                return response()->json(['status' => 'error', 'message' => 'Hanya pekerjaan yang sedang diproses yang bisa diselesaikan'], 400);
            }
            $p->status = 'selesai';
            $p->save();
            return response()->json(['status' => 'success', 'data' => $p]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyelesaikan pekerjaan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
