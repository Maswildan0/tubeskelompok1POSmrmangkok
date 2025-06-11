<?php

use App\Models\Karyawan;
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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->string('nama_karyawan');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->decimal('total_jam', 8, 2);
            $table->decimal('upah_per_jam', 10, 2);
            $table->decimal('total_gaji', 12, 2);
            $table->date('tanggal_pembayaran')->nullable();
            $table->enum('status', ['belum dibayar', 'sudah dibayar'])->default('belum dibayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
