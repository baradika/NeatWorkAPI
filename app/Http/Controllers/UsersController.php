<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
