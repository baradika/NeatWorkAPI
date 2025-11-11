<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PetugasProfile;
use App\Http\Requests\StorePetugasProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function checkPetugasProfile()
    {
        $user = auth()->user();
        Log::info('Checking petugas profile for user ID: ' . $user->id_user);
        $profile = PetugasProfile::where('id_user', $user->id_user)->first();
        $hasProfile = !is_null($profile);
        $isVerified = $hasProfile && $profile->status === 'approved';
        Log::info(sprintf(
            'Profile check - User ID: %s, Has Profile: %s, Status: %s',
            $user->id_user,
            $hasProfile ? 'Yes' : 'No',
            $hasProfile ? $profile->status : 'N/A'
        ));
        
        return response()->json([
            'status' => 'success',
            'has_profile' => $hasProfile,
            'is_verified' => $isVerified,
            'profile_status' => $hasProfile ? $profile->status : null,
            'user_id' => $user->id_user
        ]);
    }

    public function storePetugasProfile(StorePetugasProfileRequest $request)
    {
        try {
            $user = auth()->user();
            if ($user->petugasProfile) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah mengajukan verifikasi petugas sebelumnya',
                ], 400);
            }
            $ktpPhotoPath = $request->file('ktp_photo')->store('petugas/ktp', 'public');
            $selfieWithKtpPath = $request->file('selfie_with_ktp')->store('petugas/selfie', 'public');
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
            $user->petugasProfile()->save($profile);
            return response()->json([
                'status' => 'success',
                'message' => 'Profil petugas berhasil diajukan. Menunggu verifikasi admin.',
                'data' => $profile
            ], 201);
            
        } catch (\Exception $e) {
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
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
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
