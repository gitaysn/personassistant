<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subkriteria;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class SubkriteriaController extends Controller
{
    // Menampilkan data berdasarkan kriteria
    public function showByKriteria($id)
    {
        // Ambil data kriteria berdasarkan ID
        $kriteria = Kriteria::findOrFail($id);

        // Ambil data subkriteria berdasarkan subkriteria_id, jika menggunakan kolom ini
        $subkriterias = SubKriteria::where('kriteria_id', $id)->get(); // Periksa kolom yang digunakan

        // Kirim data ke view
        return view('admin.pages.subkriteria.dynamic', compact('kriteria', 'subkriterias'));
    }

    // Menampilkan form untuk membuat subkriteria
    public function create($id)
    {
        // Ambil data kriteria berdasarkan ID
        $kriteria = Kriteria::findOrFail($id);

        // Kirim data ke view
        return view('admin.pages.subkriteria.create', compact('kriteria'));
    }

    // Menyimpan data subkriteria baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'kriteria_id' => 'required|exists:datakriteria,id',
            'name' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0.01',
        ], [
            'nilai.min' => 'Nilai tidak boleh 0.',
        ]);

        // Simpan data subkriteria
        SubKriteria::create([
            'kriteria_id' => $validated['kriteria_id'],
            'nama_subkriteria' => $validated['name'],
            'nilai' => $validated['nilai'],
        ]);

        // Redirect kembali ke halaman subkriteria berdasarkan kriteria_id
        return redirect()->route('admin.pages.subkriteria.kriteria', ['id' => $validated['kriteria_id']])
                 ->with('success', 'Subkriteria berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Ambil data subkriteria berdasarkan subkriteria_id
        $subkriteria = SubKriteria::find($id);

        if (!$subkriteria) {
            return redirect()->back()->with('error', 'Subkriteria tidak ditemukan.');
        }

        // Ambil data kriteria terkait
        $kriteria = Kriteria::findOrFail($subkriteria->kriteria_id);

        // Kirim data ke view
        return view('admin.pages.subkriteria.edit', compact('subkriteria', 'kriteria'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'kriteria_id' => 'required|exists:datakriteria,id',
            'name' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0.01',
        ], [
            'nilai.min' => 'Nilai tidak boleh 0.',
        ]);

        // Ambil data subkriteria berdasarkan ID
        $subkriteria = SubKriteria::findOrFail($id); // Pastikan menggunakan subkriteria_id jika diperlukan

        // Update data subkriteria
        $subkriteria->update([
            'kriteria_id' => $validated['kriteria_id'],
            'nama_subkriteria' => $validated['name'],
            'nilai' => $validated['nilai'],
        ]);

        // Redirect kembali ke halaman subkriteria berdasarkan kriteria_id
        return redirect()->route('admin.pages.subkriteria.kriteria', ['id' => $validated['kriteria_id']])
                ->with('success', 'Subkriteria berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Cari subkriteria berdasarkan ID
        $subkriteria = SubKriteria::find($id);

        // Jika tidak ditemukan, redirect balik dengan pesan error
        if (!$subkriteria) {
            return redirect()->back()->with('error', 'Subkriteria tidak ditemukan.');
        }

        // Hapus subkriteria
        $subkriteria->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Subkriteria berhasil dihapus.');
    }

}
