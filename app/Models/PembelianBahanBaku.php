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

class PembelianBahanBaku extends Model
{
    /** @use HasFactory<\Database\Factories\PembelianBahanBakuFactory> */
    use HasFactory;

    protected $table = 'pembelianbahanbaku'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getIdPembelian()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(id_pembelian), 'PRC-0000000') as id_pembelian 
                FROM pembelianbahanbaku ";
        $kodeidpemb = DB::select($sql);

        // cacah hasilnya
        foreach ($kodeidpemb as $kdpmbl) {
            $kd = $kdpmbl->id_pembelian;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-7);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'PRC-'.str_pad($noakhir,7,"0",STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;

    }

    public static function getvirtualccount()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(id_pembelian), 'VA-0000000') as virtual_account 
                FROM pembayaran ";
        $kodeidpemb = DB::select($sql);

        // cacah hasilnya
        foreach ($kodevirtualaccount as $kdva) {
            $kd = $kdpmbl->id_pembelian;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-7);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'VA-'.str_pad($noakhir,7,"0",STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;

    }

    // relasi ke tabel supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kode_supplier');
    }

    // relasi ke tabel pemb bhn bk
    public function DataPembelian()
    {
      return $this->hasMany(DataPembelian::class, 'id_pembelian');
    }

    public function bahanbaku()
    {
        return $this->belongsTo(bahanbaku::class, 'kode_bahanbaku');
    }
}
