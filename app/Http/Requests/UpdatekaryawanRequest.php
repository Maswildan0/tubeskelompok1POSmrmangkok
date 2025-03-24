<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKaryawanRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk melakukan permintaan ini.
     */
    public function authorize(): bool
    {
        return true; // Pastikan diubah ke 'true' agar dapat digunakan
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_karyawan' => 'required|exists:karyawans,id',
            'nama_karyawan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'required|string|max:15|unique:karyawans,no_telepon,' . $this->route('karyawan'),
            'email' => 'required|email|unique:karyawans,email,' . $this->route('karyawan'),
            'alamat' => 'required|string|max:500',
        ];
    }
}
