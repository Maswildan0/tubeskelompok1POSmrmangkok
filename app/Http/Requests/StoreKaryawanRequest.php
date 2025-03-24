<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaryawanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ganti dengan true agar request ini diizinkan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_karyawan' => 'required|unique:karyawans,id_karyawan|max:10', // Validasi id karyawan
            'nama_karyawan' => 'required|string|max:255', // Validasi nama karyawan
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan', // Validasi jenis kelamin
            'alamat' => 'required|string|max:255', // Validasi alamat
            'nomor_telepon' => 'required|unique:karyawans,nomor_telepon|regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/', // Validasi nomor telepon
            'email' => 'required|email|unique:karyawans,email', // Validasi email
        ];
    }
}
