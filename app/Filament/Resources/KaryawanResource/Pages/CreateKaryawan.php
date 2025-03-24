<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Filament\Resources\KaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    /**
     * Menentukan kolom yang tampil di form pembuatan karyawan.
     *
     * @return array
     */
    protected function getFormColumns(): array
    {
        return [
            // Tentukan kolom atau field yang akan ditampilkan dalam form
            'id_karyawan' => 'required|string|max:255', // Menambahkan id_karyawan
            'nama_karyawan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:500',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'required|email|unique:karyawans,email',
        ];
    }

    /**
     * Fungsi ini untuk mendefinisikan aksi yang dapat dilakukan di halaman.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            // Menambahkan aksi lainnya jika diperlukan
            Actions\DeleteAction::make(),
        ];
    }
}
