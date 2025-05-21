<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataKriteria;
use App\Models\Kriteria;
use Dflydev\DotAccessData\Data;

class DataKriteriaController extends Controller
{
    // Menampilkan daftar kriteria
    public function indexPage()
    {
        $kriteria = Kriteria::orderBy('kode_kriteria', 'asc')->get();
        return view('admin.pages.kriteria.index', compact('kriteria'));
    }

    // Menampilkan form tambah data
    public function create()
    {
        return view('admin.pages.kriteria.create');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        // Validasi inputan dari form
        $request->validate([
            'kode_kriteria' => 'required|unique:datakriteria,kode_kriteria|max:10',
            'nama_kriteria' => 'required|max:255',
            'bobot' => 'required|numeric|min:0.01|max:1', // nilai > 0
            'jenis' => 'required|in:Benefit,Cost',
        ], [
            'bobot.min' => 'Bobot tidak boleh 0.',
        ]);
        
         // Simpan data kriteria baru ke database
        Kriteria::create($request->only(['kode_kriteria', 'nama_kriteria', 'bobot', 'jenis']));

        return redirect()->route('admin.kriteria.index')->with('success', 'Data Kriteria berhasil ditambahkan!');
    }

    // Menampilkan form edit data
    public function edit($id)
    {
         // Cari data kriteria berdasarkan ID
        $kriteria = Kriteria::findOrFail($id);
        return view('admin.pages.kriteria.edit', compact('kriteria'));
    }

    // Menyimpan perubahan data
    public function update(Request $request, $id)
    {
        // Validasi inputan dari form edit
        $request->validate([
            'kode_kriteria' => "required|max:10|unique:datakriteria,kode_kriteria,$id,id",
            'nama_kriteria' => 'required|max:255',
            'bobot' => 'required|numeric|min:0.01|max:1', // nilai > 0
            'jenis' => 'required|in:Benefit,Cost',
        ], [
            'bobot.min' => 'Bobot tidak boleh 0.',
        ]);
        
        // Cari data kriteria yang akan diupdate
        $kriteria = Kriteria::findOrFail($id);
        // Update data dengan input yang diberikan
        $kriteria->update($request->only(['kode_kriteria', 'nama_kriteria', 'bobot', 'jenis']));

        return redirect()->route('admin.kriteria.index')->with('success', 'Data Kriteria berhasil diperbarui!');
    }

    // Menghapus data kriteria
    public function destroy($id)
    {
        // Cari data kriteria berdasarkan ID
        $kriteria = Kriteria::findOrFail($id);
        // Hapus data dari database
        $kriteria->delete();

        return redirect()->route('admin.kriteria.index')->with('success', 'Data Kriteria berhasil dihapus!');
    }
}
