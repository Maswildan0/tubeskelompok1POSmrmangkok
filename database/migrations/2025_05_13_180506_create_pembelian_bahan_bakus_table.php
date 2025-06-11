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
        Schema::create('pembelianbahanbaku', function (Blueprint $table) {
            $table->id();
            $table->string('id_pembelian')->unique;
            $table->foreignId('kode_supplier')->constrained('supplier')->onDelete('cascade');
            $table->string('nama_supplier');
            $table->string('status'); 
            $table->datetime('tgl'); 
            $table->decimal('tagihan', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelianbahanbaku');
    }
};
