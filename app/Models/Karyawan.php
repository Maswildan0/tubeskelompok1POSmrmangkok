<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;


class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan'; // Nama tabel eksplisit

    protected $guarded = []; //semua kolom boleh di isi

    public static function getKodeKaryawan()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(id_karyawan), 'KAR-00000') as id_karyawan
                FROM Karyawan ";
        $idkaryawan = DB::select($sql);

        // cacah hasilnya
        foreach ($idkaryawan as $kdkrywn) {
            $kd = $kdkrywn->id_karyawan;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-5);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'KAR-'.str_pad($noakhir,5,"0",STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;

    }

    // relasi ke tabel pembeli
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
        // pastikan 'user_id' adalah nama kolom foreign key
    }
     // Menambahkan relasi untuk menghitung total jam kerja berdasarkan absensi
     public function penggajians()
     {
         return $this->hasMany(Penggajian::class, 'id_karyawan');
     }
     
     public function absensis()
     {
         return $this->hasMany(Absensi::class, 'id_karyawan');
     }
     


    public static function boot()
    {
        parent::boot();

        static::creating(function ($karyawan) {
            // Generate id_karyawan jika belum ada
            if (empty($karyawan->id_karyawan)) {
                $karyawan->id_karyawan = 'KA' . str_pad(Karyawan::count() + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // Menghitung total jam kerja berdasarkan absensi
    public function getTotalJamKerjaAttribute()
{
    return $this->absensi()->get()->map(function ($absen) {
        // Pastikan jam_masuk dan jam_keluar diubah ke objek Carbon dan dihitung dalam jam
        $masuk = \Carbon\Carbon::parse($absen->jam_masuk);
        $keluar = \Carbon\Carbon::parse($absen->jam_keluar);
        return $masuk->diffInHours($keluar);
    })->sum(); // Setelah itu lakukan sum pada hasilnya
}
}
