<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email|max:100|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'sometimes|in:admin,petugas,pelanggan',
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'role.in' => 'Role tidak valid',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            $nama = explode('@', $validated['email'])[0];
            $role = $validated['role'] ?? 'pelanggan';

            $user = User::create([
                'nama' => $nama,
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $role,
                'created_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat melakukan registrasi',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    // ... rest of the file remains the same ...
    public function checkEmail(Request $request)
    {
        try {
            \Log::info('Check Email Request:', $request->all());
            
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email',
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
            ]);

            if ($validator->fails()) {
                \Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            \DB::enableQueryLog();
            $exists = User::where('email', $request->email)->exists();
            $query = \DB::getQueryLog();
            \Log::info('Database Query:', $query);

            $response = [
                'status' => 'success',
                'data' => [
                    'email' => $request->email,
                    'exists' => $exists
                ]
            ];

            \Log::info('Check Email Response:', $response);
            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Check Email Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memeriksa email',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password harus diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('email', 'password');
            
            if (!\Auth::once($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email atau password salah'
                ], 401);
            }

            $user = \Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'data' => $user,
                'token' => $token
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat login',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->user()?->currentAccessToken();
            if ($token) {
                $token->delete();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Logout berhasil'
            ]);
        } catch (\Exception $e) {
            \Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat logout',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }
}
