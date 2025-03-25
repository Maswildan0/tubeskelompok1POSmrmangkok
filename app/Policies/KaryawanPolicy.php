<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Karyawan;

class KaryawanPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat semua model.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat melihat model tertentu.
     */
    public function view(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat membuat model baru.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui model.
     */
    public function update(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model.
     */
    public function delete(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat mengembalikan (restore) model.
     */
    public function restore(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model secara permanen.
     */
    public function forceDelete(User $user, Karyawan $karyawan): bool
    {
        return true;
    }
}
