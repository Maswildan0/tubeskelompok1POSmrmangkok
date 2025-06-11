<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penggajian extends Model
{
    protected $table = 'penggajian';
    protected $guarded = [];

    public static function getIdGaji()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(id_gaji), 'G0000') as id_gaji
                FROM penggajian ";
        $idgaji = DB::select($sql);

        // cacah hasilnya
        foreach ($idgaji as $idgji) {
            $gj = $idgji->id_gaji;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($gj,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'G'.str_pad($noakhir,4,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
        return $noakhir;

    }
}
