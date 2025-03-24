<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    /** @use HasFactory<\Database\Factories\KaryawanFactory> */
    use HasFactory;

    // Menentukan nama tabel jika berbeda dari konvensi plural
    protected $table = 'karyawans';

    // Menentukan atribut yang dapat diisi secara massal
    protected $fillable = [
        'id_karyawan',
        'nama_karyawan',
        'jenis_kelamin',
        'no_telepon',
        'email',
        'alamat',
    ];

    // Jika id_karyawan bukan auto-increment dan ingin diatur manual, Anda bisa tentukan
    public $incrementing = false; // Menandakan id_karyawan tidak auto-increment

    // Tentukan tipe data untuk id_karyawan (misalnya jika ID adalah string)
    protected $keyType = 'string';
}
