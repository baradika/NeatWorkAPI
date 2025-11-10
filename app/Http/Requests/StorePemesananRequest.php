<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemesananRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jenis_service_id' => 'required|exists:jenis_services,id',
            'alamat' => 'required|string|max:1000',
            'service_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:1',
            'preferred_gender' => 'required|in:any,male,female',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'jenis_service_id.required' => 'Jenis layanan harus dipilih',
            'jenis_service_id.exists' => 'Jenis layanan tidak valid',
            'alamat.required' => 'Alamat harus diisi',
            'service_date.required' => 'Tanggal layanan harus diisi',
            'service_date.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini',
            'duration.required' => 'Durasi harus diisi',
            'duration.min' => 'Durasi minimal 1 jam',
            'preferred_gender.required' => 'Pilih preferensi gender staf',
            'preferred_gender.in' => 'Preferensi gender tidak valid',
        ];
    }
}
