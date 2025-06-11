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
        Schema::create('BahanBaku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahanbaku')->unique;
            $table->string('nama_bahanbaku');
            $table->foreignId('kode_supplier')->constrained('supplier');
            $table->string('nama_supplier');
            $table->string('foto');
            $table->string('stok');
            $table->string('satuan');
            $table->string('harga_beli');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BahanBaku');
    }
};
