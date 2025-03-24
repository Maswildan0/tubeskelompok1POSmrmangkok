<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('karyawans')->insert([
            [
                'nama_karyawan' => 'Asep Nugraha',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'nomor_telepon' => '081234567890',
                'email' => 'asep@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_karyawan' => 'Siti Aminah',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Anggrek No. 25, Bandung',
                'nomor_telepon' => '081298765432',
                'email' => 'siti@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
