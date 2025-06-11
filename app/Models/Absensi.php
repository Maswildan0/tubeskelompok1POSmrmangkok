<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi'; 
    protected $guarded = [];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
    public function getTotalJamHariIniAttribute()
{
    if (!$this->jam_masuk || !$this->jam_keluar) {
        return 0;
    }

    $masuk = strtotime($this->jam_masuk);
    $keluar = strtotime($this->jam_keluar);

    if ($keluar > $masuk) {
        $selisihMenit = ($keluar - $masuk) / 60;
        return number_format($selisihMenit / 60, 2);
    }

    return 0;
}

    public static function boot()
    {
        parent::boot();

        static::creating(function ($absensi) {
            $karyawan = \App\Models\Karyawan::find($absensi->id_karyawan);

            if ($karyawan) {
                $absensi->nama_karyawan = $karyawan->nama_karyawan;
            }
        });

        static::updating(function ($absensi) {
            $karyawan = \App\Models\Karyawan::find($absensi->id_karyawan);
            
        });
    }

    
}
