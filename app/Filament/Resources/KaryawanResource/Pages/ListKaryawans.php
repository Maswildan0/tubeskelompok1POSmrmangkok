<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Filament\Resources\KaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKaryawans extends ListRecords
{
    protected static string $resource = KaryawanResource::class;

    /**
     * Mendapatkan aksi header (misalnya, tombol buat karyawan baru)
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // Aksi untuk membuat karyawan baru
        ];
    }

    /**
     * Menentukan kolom yang akan ditampilkan pada tabel.
     *
     * @return array
     */
    protected function getTableColumns(): array
    {
        return [
            // Menampilkan kolom-kolom yang relevan untuk daftar karyawan
            'id_karyawan' => 'ID Karyawan',
            'nama_karyawan' => 'Nama Karyawan',
            'jenis_kelamin' => 'Jenis Kelamin',
            'email' => 'Email',
            'nomor_telepon' => 'Nomor Telepon',
        ];
    }

    /**
     * Menentukan kolom yang akan ditampilkan pada aksi baris tabel.
     *
     * @return array
     */
    protected function getTableActions(): array
    {
        return [
            // Menambahkan aksi untuk mengedit karyawan
            Actions\EditAction::make(),
            // Menambahkan aksi untuk menghapus karyawan
            Actions\DeleteAction::make(),
        ];
    }
}
