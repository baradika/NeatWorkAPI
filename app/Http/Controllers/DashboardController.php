<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $bookings = Pemesanan::where('user_id', $user->id_user)
            ->orderBy('service_date', 'asc')
            ->get(['status', 'service_date']);

        $now = now();
        $active = $bookings->filter(function ($b) {
            return strtolower((string)$b->status) !== 'selesai';
        });

        $next = $active->map(function ($b) {
            return $b->service_date ? \Carbon\Carbon::parse($b->service_date) : null;
        })->filter(function ($d) use ($now) {
            return $d && $d->gte($now);
        })->sort()->first();

        return response()->json([
            'data' => [
                'active_count' => $active->count(),
                'next_schedule' => $next ? $next->toDateString() : null,
            ]
        ]);
    }
}
