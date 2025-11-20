<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    public function reverse(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');
        if ($lat === null || $lon === null) {
            return response()->json(['message' => 'lat and lon are required'], 422);
        }

        $url = 'https://nominatim.openstreetmap.org/reverse';
        $response = Http::withHeaders([
            'User-Agent' => 'NeatWorkAPI/1.0 (reverse-geocode)'
        ])->get($url, [
            'format' => 'jsonv2',
            'lat' => $lat,
            'lon' => $lon,
            'addressdetails' => 1,
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to reverse geocode'], 502);
        }

        return response($response->body(), 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function search(Request $request)
    {
        $q = $request->query('q');
        if (!$q) {
            return response()->json(['message' => 'q is required'], 422);
        }
        $url = 'https://nominatim.openstreetmap.org/search';
        $response = Http::withHeaders([
            'User-Agent' => 'NeatWorkAPI/1.0 (search)'
        ])->get($url, [
            'format' => 'jsonv2',
            'q' => $q,
            'limit' => 5,
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to search address'], 502);
        }

        return response($response->body(), 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
