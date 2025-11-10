<?php

namespace App\Http\Controllers;

use App\Models\JenisService;
use App\Http\Requests\StoreJenisServiceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class JenisServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $services = JenisService::all();
        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJenisServiceRequest $request): JsonResponse
    {
        try {
            $service = JenisService::create($request->validated());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Jenis service berhasil ditambahkan',
                'data' => $service
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan jenis service',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $service = JenisService::find($id);
        
        if (!$service) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreJenisServiceRequest $request, string $id): JsonResponse
    {
        try {
            $service = JenisService::find($id);
            
            if (!$service) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $service->update($request->validated());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Jenis service berhasil diperbarui',
                'data' => $service
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui jenis service',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $service = JenisService::find($id);
            
            if (!$service) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $service->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Jenis service berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus jenis service',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
