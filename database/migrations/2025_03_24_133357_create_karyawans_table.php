<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            // Kolom id_karyawan sebagai primary key dan bukan auto-increment
            $table->string('id_karyawan')->primary(); // Menggunakan string sebagai id_karyawan

            // Kolom lainnya
            $table->string('nama_karyawan');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('alamat');
            $table->string('nomor_telepon');
            $table->string('email')->unique();

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
