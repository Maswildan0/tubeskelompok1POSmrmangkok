<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Filament\Resources\KaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKaryawan extends EditRecord
{
    protected static string $resource = KaryawanResource::class;

    /**
     * Mendapatkan aksi header (misalnya, tombol hapus)
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(), // Menambahkan aksi hapus
        ];
    }

    /**
     * Mendapatkan kolom-kolom yang ditampilkan di form edit.
     *
     * @return array
     */
    protected function getFormColumns(): array
    {
        return [
            'id_karyawan' => 'required|string|max:255', // Menampilkan id_karyawan
            'nama_karyawan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:500',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'required|email|unique:karyawans,email,' . $this->record->id,
        ];
    }
}
