<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Http\Requests\StoreKaryawanRequest;
use App\Http\Requests\UpdateKaryawanRequest;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data karyawan dari database dan menampilkannya
        $karyawans = Karyawan::all();
        return view('karyawan.index', compact('karyawans')); // Ganti dengan view yang sesuai
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form untuk membuat karyawan baru
        return view('karyawan.create'); // Ganti dengan view yang sesuai
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKaryawanRequest $request)
    {
        // Validasi dan menyimpan data karyawan baru ke dalam database
        $data = $request->validated();

        Karyawan::create($data); // Menyimpan data karyawan baru
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        // Menampilkan detail karyawan tertentu
        return view('karyawan.show', compact('karyawan')); // Ganti dengan view yang sesuai
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        // Menampilkan form untuk mengedit data karyawan
        return view('karyawan.edit', compact('karyawan')); // Ganti dengan view yang sesuai
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKaryawanRequest $request, Karyawan $karyawan)
    {
        // Validasi dan memperbarui data karyawan
        $data = $request->validated();

        $karyawan->update($data); // Memperbarui data karyawan
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        // Menghapus data karyawan
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
