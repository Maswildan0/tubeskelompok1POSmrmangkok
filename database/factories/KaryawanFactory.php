<?php

namespace Database\Factories;

use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Tentukan model yang sesuai untuk factory ini.
     *
     * @return string
     */
    protected $model = Karyawan::class;

    /**
     * Tentukan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_karyawan' => $this->faker->unique()->bothify('KAR-####'), // ID Karyawan dengan format KAR-####
            'nama_karyawan' => $this->faker->name, // Nama karyawan acak
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']), // Jenis kelamin acak
            'alamat' => $this->faker->address, // Alamat acak
            'nomor_telepon' => $this->faker->phoneNumber, // Nomor telepon acak
            'email' => $this->faker->safeEmail, // Email acak
        ];
    }
}
