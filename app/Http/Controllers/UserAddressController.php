<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $items = UserAddress::where('user_id', $user->id_user)
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();
        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'label' => 'required|string|max:100',
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_default' => 'sometimes|boolean',
        ]);
        $data['user_id'] = $user->id_user;

        if (!empty($data['is_default'])) {
            UserAddress::where('user_id', $user->id_user)->update(['is_default' => false]);
        }

        $addr = UserAddress::create($data);
        return response()->json(['data' => $addr], 201);
    }

    public function update(Request $request, string $id)
    {
        $user = $request->user();
        $addr = UserAddress::where('user_id', $user->id_user)->findOrFail($id);
        $data = $request->validate([
            'label' => 'sometimes|required|string|max:100',
            'alamat' => 'sometimes|required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_default' => 'sometimes|boolean',
        ]);

        if (array_key_exists('is_default', $data) && $data['is_default']) {
            UserAddress::where('user_id', $user->id_user)->where('id', '!=', $addr->id)->update(['is_default' => false]);
        }

        $addr->update($data);
        return response()->json(['data' => $addr]);
    }

    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        $addr = UserAddress::where('user_id', $user->id_user)->findOrFail($id);
        $addr->delete();
        return response()->json(null, 204);
    }
}
