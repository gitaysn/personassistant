<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataAlternatif;
use App\Models\DataKriteria;
use App\Models\Kriteria;
use Illuminate\Support\Str;

class AlternatifController extends Controller
{
    public function indexPage(Request $request)
    {
        // Ambil semua subkriteria tanpa filter "Jenis Pakaian"
        $subkriteria = \App\Models\Subkriteria::where('kriteria_id', 3)->get();

        // Ambil semua kriteria
        $kriteria = Kriteria::all();

        // Ambil semua alternatif dengan relasi ke subkriteria
        $query = DataAlternatif::with('subkriteria', 'penilaian');

        // Jika ada filter jenis pakaian, gunakan filter berdasarkan subkriteria_id
        if ($request->filled('jenis_pakaian')) {
            $query->where('subkriteria_id', $request->jenis_pakaian);
        }

         // Search
        if ($request->filled('search')) {
            $query->where('nama_alternatif', 'like', '%' . $request->search . '%');
        }

        // Ambil data alternatif dengan pagination (10 per halaman)
        $perPage = $request->input('entries', 10); // Ambil dari URL ?entries=xx, default 10
        $alternatif = $query->paginate($perPage)->appends($request->query());

        // Kirim data ke view
        return view('admin.pages.alternatif.index', compact('subkriteria', 'alternatif', 'kriteria'));
    }


    public function store(Request $request)
    {
         // Validasi input
        $request->validate([
            'nama_alternatif' => 'required|string|max:255|unique:dataalternatif,nama_alternatif',
            'subkriteria_id' => 'required|exists:subkriteria,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gambarPath = null;

        // Jika ada gambar yang diunggah, simpan ke folder public/assets/img
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFile = time() . '_' . $gambar->getClientOriginalName();
            // simpan ke public/assets/img
            $gambar->move(public_path('assets/img'), $namaFile);
            $gambarPath = 'assets/img/' . $namaFile;
        }

        // Simpan data alternatif ke database
        $alternatif = DataAlternatif::create([
            'nama_alternatif' => $request->nama_alternatif,
            'subkriteria_id' => $request->subkriteria_id,
            'gambar' => $gambarPath,
        ]);

        // Ambil semua kriteria yang ada
        $kriteria = Kriteria::all();

        // Simpan nilai awal 0 untuk semua kriteria pada alternatif baru
        foreach ($kriteria as $k) {
            \App\Models\Penilaian::create([
                'alternatif_id' => $alternatif->id,
                'kriteria_id' => $k->id,
                'nilai' => 0, // Atur nilai default
            ]);
        }

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.alternatif.index')->with('success', 'Data Alternatif berhasil ditambahkan.');
    }

    // Menampilkan form edit untuk alternatif
    public function edit($id)
    {
         // Ambil data alternatif berdasarkan ID
        $alternatif = DataAlternatif::findOrFail($id);

        // Ambil semua subkriteria (untuk pilihan dropdown misalnya)
        $subkriteria = \App\Models\Subkriteria::all();

         // Tampilkan view edit
        return view('admin.pages.alternatif.edit', compact('alternatif', 'subkriteria'));
    }

    public function update(Request $request, $id)
    {
         // Tampilkan view edit
        $request->validate([
            'nama_alternatif' => 'required|string|max:255|unique:dataalternatif,nama_alternatif,' . $id,
            'subkriteria_id' => 'required|exists:subkriteria,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ambil data alternatif berdasarkan ID
        $alternatif = DataAlternatif::findOrFail($id);

        // Update data dari input
        $alternatif->nama_alternatif = $request->nama_alternatif;
        $alternatif->subkriteria_id = $request->subkriteria_id;

        // Jika user mengunggah gambar baru, simpan dan update path-nya
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();

            // Simpan di public/assets/img
            $file->move(public_path('assets/img'), $namaFile);

            // Update path di DB
            $alternatif->gambar = 'assets/img/' . $namaFile;
        }

        // Simpan perubahan ke database
        $alternatif->save();

        // Jika permintaan AJAX, kirim response JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data Alternatif berhasil diperbarui!']);
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.alternatif.index')->with('success', 'Data Alternatif berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        // Cari data berdasarkan ID dan hapus
        $alternatif = DataAlternatif::findOrFail($id);
        $alternatif->delete();

        // Jika permintaan AJAX, kirim response JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.alternatif.index')->with('success', 'Data Alternatif berhasil dihapus.');
    }
}
