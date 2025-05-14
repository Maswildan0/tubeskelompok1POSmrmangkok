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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id(); // Menambahkan id sebagai primary key auto-increment
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('id_karyawan');  // Kolom untuk ID Karyawan
            $table->string('nama_karyawan');  // Kolom untuk Nama Karyawan
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);  // Kolom untuk Jenis Kelamin
            $table->string('alamat');  // Kolom untuk Alamat
            $table->string('nomor_telepon');  // Kolom untuk Nomor Telepon
            $table->string('jabatan');  // Kolom untuk Nomor Telepon
            $table->string('total_hari');  // Kolom untuk Nomor Telepon
            $table->timestamps();  // Menambahkan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
