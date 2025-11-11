<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetugasProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ktp_number' => 'required|digits:16|unique:petugas_profiles,ktp_number',
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'selfie_with_ktp' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'full_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'ktp_number.required' => 'Nomor KTP harus diisi',
            'ktp_number.digits' => 'Nomor KTP harus 16 digit angka',
            'ktp_number.unique' => 'Nomor KTP sudah terdaftar',
            'ktp_photo.required' => 'Foto KTP harus diupload',
            'ktp_photo.image' => 'File harus berupa gambar',
            'ktp_photo.mimes' => 'Format file harus jpeg, png, atau jpg',
            'ktp_photo.max' => 'Ukuran file maksimal 5MB',
            'selfie_with_ktp.required' => 'Foto selfie dengan KTP harus diupload',
            'selfie_with_ktp.image' => 'File harus berupa gambar',
            'selfie_with_ktp.mimes' => 'Format file harus jpeg, png, atau jpg',
            'selfie_with_ktp.max' => 'Ukuran file maksimal 5MB',
            'full_name.required' => 'Nama lengkap harus diisi',
            'date_of_birth.required' => 'Tanggal lahir harus diisi',
            'date_of_birth.before' => 'Tanggal lahir tidak valid',
            'phone_number.required' => 'Nomor telepon harus diisi',
            'address.required' => 'Alamat lengkap harus diisi',
        ];
    }
}
