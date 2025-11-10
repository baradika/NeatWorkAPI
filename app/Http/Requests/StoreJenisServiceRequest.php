<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'kode_service' => 'required|string|max:10|unique:jenis_services,kode_service',
            'nama_service' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'estimasi_waktu' => 'required|integer|min:1',
        ];

        // For update, ignore current record for unique validation
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['kode_service'] = 'required|string|max:10|unique:jenis_services,kode_service,' . $this->route('jenis_service');
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'kode_service.required' => 'Kode service harus diisi',
            'kode_service.unique' => 'Kode service sudah digunakan',
            'kode_service.max' => 'Kode service maksimal 10 karakter',
            'nama_service.required' => 'Nama service harus diisi',
            'nama_service.max' => 'Nama service maksimal 100 karakter',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga minimal 0',
            'estimasi_waktu.required' => 'Estimasi waktu harus diisi',
            'estimasi_waktu.integer' => 'Estimasi waktu harus berupa angka',
            'estimasi_waktu.min' => 'Estimasi waktu minimal 1 menit',
        ];
    }
}
