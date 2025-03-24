<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Auth\Access\Response;

class KaryawanPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat semua model.
     */
    public function viewAny(User $user): bool
    {
        // Hanya admin yang dapat melihat semua karyawan
        return $user->role === 'admin';
    }

    /**
     * Tentukan apakah pengguna dapat melihat model tertentu.
     */
    public function view(User $user, Karyawan $karyawan): bool
    {
        // Pengguna dapat melihat data karyawan miliknya atau admin dapat melihat semua data karyawan
        return $user->id === $karyawan->user_id || $user->role === 'admin';
    }

    /**
     * Tentukan apakah pengguna dapat membuat model baru.
     */
    public function create(User $user): bool
    {
        // Hanya admin yang dapat membuat karyawan
        return $user->role === 'admin';
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui model.
     */
    public function update(User $user, Karyawan $karyawan): bool
    {
        // Hanya admin yang dapat memperbarui semua data karyawan atau pengguna dapat memperbarui data karyawan miliknya
        return $user->id === $karyawan->user_id || $user->role === 'admin';
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model.
     */
    public function delete(User $user, Karyawan $karyawan): bool
    {
        // Hanya admin yang dapat menghapus semua data karyawan atau pengguna dapat menghapus data karyawan miliknya
        return $user->id === $karyawan->user_id || $user->role === 'admin';
    }

    /**
     * Tentukan apakah pengguna dapat mengembalikan (restore) model.
     */
    public function restore(User $user, Karyawan $karyawan): bool
    {
        // Hanya admin yang dapat mengembalikan data karyawan
        return $user->role === 'admin';
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model secara permanen.
     */
    public function forceDelete(User $user, Karyawan $karyawan): bool
    {
        // Hanya admin yang dapat menghapus data karyawan secara permanen
        return $user->role === 'admin';
    }
}
