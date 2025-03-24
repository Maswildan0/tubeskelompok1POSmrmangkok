<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;


class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getIdKategori()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(id_kategori), 'KTG000') as id_kategori 
                FROM kategori ";
        $idkategori = DB::select($sql);

        // cacah hasilnya
        foreach ($idkategori as $idktg) {
            $ktg = $idktg->id_kategori;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($ktg,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'KTG'.str_pad($noakhir,3,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
        return $noakhir;

    }
    public function getJenisKategoriAttribute($value)
{
    return $value;
}
}
