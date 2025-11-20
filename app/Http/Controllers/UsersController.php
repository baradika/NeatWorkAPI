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
use Illuminate\Support\Facades\Mail;
use App\Mail\PetugasProfileApproved;
use App\Mail\PetugasProfileRejected;

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

    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return response()->json(['data' => $user]);
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
        $profile = PetugasProfile::where('user_id', $user->id_user)->first();
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
            'rejection_reason' => $hasProfile ? $profile->rejection_reason : null,
            'user_id' => $user->id_user
        ]);
    }

    public function storePetugasProfile(StorePetugasProfileRequest $request)
    {
        try {
            $user = auth()->user();
            $existing = $user->petugasProfile;
            if ($existing && $existing->status !== 'rejected') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah mengajukan verifikasi petugas sebelumnya',
                ], 400);
            }
            $ktpPhotoPath = $request->file('ktp_photo')->store('petugas/ktp', 'public');
            $selfieWithKtpPath = $request->file('selfie_with_ktp')->store('petugas/selfie', 'public');
            if ($existing && $existing->status === 'rejected') {
                // Optional: delete old files
                try {
                    if ($existing->ktp_photo_path && Storage::disk('public')->exists($existing->ktp_photo_path)) {
                        Storage::disk('public')->delete($existing->ktp_photo_path);
                    }
                    if ($existing->selfie_with_ktp_path && Storage::disk('public')->exists($existing->selfie_with_ktp_path)) {
                        Storage::disk('public')->delete($existing->selfie_with_ktp_path);
                    }
                } catch (\Exception $e) {
                    // ignore file delete errors
                }

                $existing->ktp_number = $request->ktp_number;
                $existing->ktp_photo_path = $ktpPhotoPath;
                $existing->selfie_with_ktp_path = $selfieWithKtpPath;
                $existing->full_name = $request->full_name;
                $existing->date_of_birth = $request->date_of_birth;
                $existing->phone_number = $request->phone_number;
                $existing->address = $request->address;
                $existing->gender = $request->gender;
                $existing->work_experience = $request->work_experience;
                $existing->status = 'pending';
                $existing->rejection_reason = null;
                $existing->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Pengajuan profil diperbarui. Menunggu verifikasi admin.',
                    'data' => $existing
                ], 200);
            } else {
                $profile = new PetugasProfile([
                    'ktp_number' => $request->ktp_number,
                    'ktp_photo_path' => $ktpPhotoPath,
                    'selfie_with_ktp_path' => $selfieWithKtpPath,
                    'full_name' => $request->full_name,
                    'date_of_birth' => $request->date_of_birth,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'gender' => $request->gender,
                    'work_experience' => $request->work_experience,
                    'status' => 'pending',
                ]);
                $user->petugasProfile()->save($profile);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Profil petugas berhasil diajukan. Menunggu verifikasi admin.',
                    'data' => $profile
                ], 201);
            }
            
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

    /**
     * ADMIN: List petugas profiles by status (pending/approved/rejected)
     */
    public function listPetugasProfiles(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $status = $request->query('status');
        $query = PetugasProfile::with('user');
        if ($status) {
            $query->where('status', $status);
        }
        $profiles = $query->orderByDesc('created_at')->get();
        return response()->json(['data' => $profiles]);
    }

    /**
     * ADMIN: Approve a petugas profile
     */
    public function approvePetugasProfile(string $id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $profile = PetugasProfile::query()->findOrFail($id);
        $profile->status = 'approved';
        $profile->rejection_reason = null;
        $profile->save();
        try {
            $email = $profile->user?->email;
            if ($email) {
                Mail::to($email)->send(new PetugasProfileApproved($profile));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send approval email: '.$e->getMessage());
        }
        return response()->json(['status' => 'success', 'data' => $profile]);
    }

    /**
     * ADMIN: Reject a petugas profile
     */
    public function rejectPetugasProfile(Request $request, string $id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        $profile = PetugasProfile::query()->findOrFail($id);
        $profile->status = 'rejected';
        $profile->rejection_reason = $data['rejection_reason'];
        $profile->save();
        try {
            $email = $profile->user?->email;
            if ($email) {
                Mail::to($email)->send(new PetugasProfileRejected($profile));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send rejection email: '.$e->getMessage());
        }
        return response()->json(['status' => 'success', 'data' => $profile]);
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
