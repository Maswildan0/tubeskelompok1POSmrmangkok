<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans'; // Nama tabel eksplisit

    protected $guarded = []; // Menjaga agar tidak ada atribut yang dibatasi untuk mass assignment

    public static function getIdKaryawan()
    {
        // Query untuk mendapatkan ID Karyawan terakhir
        $sql = "SELECT IFNULL(MAX(id_karyawan), 'KA000') as id_karyawan FROM karyawans";
        $idkaryawan = DB::select($sql);

        // Mengambil hasil ID Karyawan
        foreach ($idkaryawan as $karyawan) {
            $id = $karyawan->id_karyawan;
        }

        // Mengambil substring tiga digit terakhir dari ID (KA-000) dan menambahkan 1
        $nomor1 = substr($id, -3); 
        $nomor2 = $nomor1 + 1; // Menambah angka 1 untuk ID berikutnya
        $nomakhir = 'KA' . str_pad($nomor2, 3, "0", STR_PAD_LEFT); // Menyusun ID Karyawan baru (KA-001)

        return $nomakhir; // Mengembalikan ID Karyawan baru
    }
}
