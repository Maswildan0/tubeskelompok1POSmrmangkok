<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPembelian extends Model
{
    /** @use HasFactory<\Database\Factories\DataPembelianFactory> */
    use HasFactory;

    protected $table = 'datapembelian';

    protected $guarded = ['id_pembelian', 'kode_bahanbaku', 'harga_beli', 'jml', 'total_pembelian', 'tgl'];

    public function pembelianbahanbaku()
    {
        return $this->belongsTo(PembelianBahanBaku::class, 'id_pembelian');
    }

    public function bahanbaku()
    {
        return $this->belongsTo(BahanBaku::class, 'kode_bahanbaku');
    }
}
