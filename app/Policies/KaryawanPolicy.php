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
HEAD
        // Hanya admin yang dapat melihat semua karyawan

        return true;
    }

    /**
     * Tentukan apakah pengguna dapat melihat model tertentu.
     */
    public function view(User $user, Karyawan $karyawan): bool
    {
 HEAD
        // Pengguna dapat melihat data karyawan miliknya atau admin dapat melihat semua data karyawan
=======
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat membuat model baru.
     */
    public function create(User $user): bool
    {
 HEAD
        // Hanya admin yang dapat membuat karyawan
=======
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui model.
     */
    public function update(User $user, Karyawan $karyawan): bool
    {
 HEAD
        // Hanya admin yang dapat memperbarui semua data karyawan atau pengguna dapat memperbarui data karyawan miliknya
=======

        return true;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model.
     */
    public function delete(User $user, Karyawan $karyawan): bool
    {
 HEAD
        // Hanya admin yang dapat menghapus semua data karyawan atau pengguna dapat menghapus data karyawan miliknya
=======

        return true;
    }

    /**
     * Tentukan apakah pengguna dapat mengembalikan (restore) model.
     */
    public function restore(User $user, Karyawan $karyawan): bool
    {
 HEAD
        // Hanya admin yang dapat mengembalikan data karyawan
=======
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model secara permanen.
     */
    public function forceDelete(User $user, Karyawan $karyawan): bool
    {
 HEAD
        // Hanya admin yang dapat menghapus data karyawan secara permanen
=======


        return true;
    }
}
