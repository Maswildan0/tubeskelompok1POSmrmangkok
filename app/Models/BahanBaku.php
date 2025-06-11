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

class BahanBaku extends Model
{
    /** @use HasFactory<\Database\Factories\BahanBakuFactory> */
    use HasFactory;

        protected $fillable = ['kode_bahanbaku', 'nama_bahanbaku', 'kode_supplier', 'nama_supplier', 'foto', 'stok', 'satuan', 'harga_beli'];

        protected $table = 'BahanBaku'; // Nama tabel eksplisit

        protected $guarded = [];

        public static function getKodeBhnBaku()
    {
        // query kode bahan baku
        $sql = "SELECT IFNULL(MAX(kode_bahanbaku), 'BB000') as kode_bahanbaku 
                FROM BahanBaku";
        $kodebahanbaku = DB::select($sql);

        // cacah hasilnya
        foreach ($kodebahanbaku as $kdbhnbk) {
            $kd = $kdbhnbk->kode_bahanbaku;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $kd = $kodebahanbaku[0]->kode_bahanbaku ?? 'BB000';
        $noawal = substr($kd, -3);               // hasilnya "000" (string)
        $noakhir = (int)$noawal + 1;             // konversi ke int, lalu tambah
        $noakhir = 'BB' . str_pad($noakhir, 3, '0', STR_PAD_LEFT); // jadi SP001, SP002, dst
        return $noakhir;

    }
    // Dengan mutator ini, setiap kali data harga_barang dikirim ke database, koma akan otomatis dihapus.
    public function setHargaBarangAttribute($value)
    {
        // Hapus koma (,) dari nilai sebelum menyimpannya ke database
        $this->attributes['harga_beli'] = str_replace('.', '', $value);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kode_supplier');
    }
     
    public function bahanbaku()
    {
        return $this->belongsTo(BahanBaku::class, 'kode_bahanbaku');
    }
    
    // Relasi dengan tabel relasi many to many nya
    public function pembelianBahanBaku()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'id_pembelian');
    }
}

