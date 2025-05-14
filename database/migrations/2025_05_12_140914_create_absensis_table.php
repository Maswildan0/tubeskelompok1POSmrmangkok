<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('id_karyawan') 
                ->constrained('karyawan') 
                ->onDelete('cascade'); // Jika data karyawan dihapus, data absensi juga ikut dihapus
            $table->string('nama_karyawan');
            $table->date('tanggal');
            $table->time('jam_masuk'); 
            $table->time('jam_keluar'); 
            $table->decimal('total_jam_hari_ini', 8, 2);  
            $table->string('bukti_foto')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
