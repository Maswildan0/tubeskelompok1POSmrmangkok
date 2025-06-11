<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\InputMask;
use Filament\Forms\Components\Select;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'Supplier'; // Nama tabel eksplisit
    
    protected $guarded = [];

    public static function getKodeSupplier()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_supplier), 'SPL000') as kode_supplier 
                FROM Supplier";
        $kodesupplier = DB::select($sql);

        // cacah hasilnya
        foreach ($kodesupplier as $kodespl) {
            $kd = $kodespl->kode_supplier;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $kd = $kodesupplier[0]->kode_supplier ?? 'SPL000';
        $noawal = substr($kd, -3);               // hasilnya "000" (string)
        $noakhir = (int)$noawal + 1;             // konversi ke int, lalu tambah
        $noakhir = 'SPL' . str_pad($noakhir, 3, '0', STR_PAD_LEFT); // jadi SP001, SP002, dst
        return $noakhir;
    }
    public function pembelianBahanBaku()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'id_pembelian');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kode_supplier');
    }

    public function dataPembelian()
    {
        return $this->hasMany(DataPembelian::class, 'id_pembelian');
    }
}

