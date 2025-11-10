<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PetugasProfile;
use App\Http\Requests\StorePetugasProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        return response()->json(User::query()->get());
    }

    public function show(string $id)
    {
        $user = User::query()->findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas,pelanggan',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'rating' => 'nullable|numeric',
        ]);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    /**
     * Store petugas profile with KTP and selfie verification
     * 
     * @param StorePetugasProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePetugasProfile(StorePetugasProfileRequest $request)
    {
        try {
            // Get the authenticated user
            $user = auth()->user();
            
            // Check if user already has a petugas profile
            if ($user->petugasProfile) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah mengajukan verifikasi petugas sebelumnya',
                ], 400);
            }
            
            // Handle file uploads
            $ktpPhotoPath = $request->file('ktp_photo')->store('petugas/ktp', 'public');
            $selfieWithKtpPath = $request->file('selfie_with_ktp')->store('petugas/selfie', 'public');
            
            // Create petugas profile
            $profile = new PetugasProfile([
                'ktp_number' => $request->ktp_number,
                'ktp_photo_path' => $ktpPhotoPath,
                'selfie_with_ktp_path' => $selfieWithKtpPath,
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'status' => 'pending',
            ]);
            
            // Save the profile
            $user->petugasProfile()->save($profile);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Profil petugas berhasil diajukan. Menunggu verifikasi admin.',
                'data' => $profile
            ], 201);
            
        } catch (\Exception $e) {
            // Delete uploaded files if an error occurs
            if (isset($ktpPhotoPath) && Storage::disk('public')->exists($ktpPhotoPath)) {
                Storage::disk('public')->delete($ktpPhotoPath);
            }
            if (isset($selfieWithKtpPath) && Storage::disk('public')->exists($selfieWithKtpPath)) {
                Storage::disk('public')->delete($selfieWithKtpPath);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengajukan profil petugas',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $user = User::query()->findOrFail($id);
        $data = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:100|unique:users,email,' . $user->id_user . ',id_user',
            'password' => 'sometimes|required|string|min:6',
            'role' => 'sometimes|required|in:admin,petugas,pelanggan',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'rating' => 'nullable|numeric',
        ]);
        $user->update($data);
        return response()->json($user);
    }

    public function destroy(string $id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
