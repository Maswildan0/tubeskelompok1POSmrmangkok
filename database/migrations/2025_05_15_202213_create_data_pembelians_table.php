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
        Schema::create('datapembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembelian')->constrained('pembelianbahanbaku')->onDelete('cascade');
            $table->foreignId('kode_bahanbaku')->constrained('bahanbaku')->onDelete('cascade');
            $table->integer('harga_beli');
            $table->integer('jml'); // jumlah barang yang dibeli
            $table->date('tgl');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datapembelian');
    }
};
