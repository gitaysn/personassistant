<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\DataAlternatif;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    // Menampilkan daftar penilaian
    public function indexPage(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Jumlah data per halaman
        $search = $request->input('search'); // Keyword pencaria

        $query = DataAlternatif::with(['penilaian.subkriteria']); // Relasi eager loading

        if ($search) {
            // Jika terdapat pencarian, filter berdasarkan nama alternatif atau nama subkriteria
            $query->where(function ($q) use ($search) {
                $q->where('nama_alternatif', 'like', "%{$search}%")
                ->orWhereHas('penilaian.subkriteria', function ($sub) use ($search) {
                    $sub->where('nama_subkriteria', 'like', "%{$search}%");
                });
            });
        }

        // Ambil data dengan pagination dan simpan parameter query kecuali halaman
        $data = $query->paginate($perPage)->appends($request->except('page'));

        // Ambil semua data kriteria untuk ditampilkan
        $kriteria = Kriteria::all();

        return view('admin.pages.penilaian.index', compact('data', 'perPage', 'kriteria', 'search'));
    }

    // Menampilkan form edit penilaian
    public function edit($id)
    {
        $alternatif = DataAlternatif::findOrFail($id); // Ambil data alternatif berdasarkan ID
        $penilaians = Penilaian::where('alternatif_id', $id)->get(); // Ambil data penilaian yang terkait
        $kriteria = Kriteria::all(); // Ambil seluruh kriteria

        $nilai = []; // Menyimpan ID subkriteria per kriteria untuk ditampilkan di form
        foreach ($penilaians as $penilaian) {
            $nilai[$penilaian->kriteria_id] = $penilaian->subkriteria_id;
        }

        return view('admin.pages.penilaian.edit', compact('alternatif', 'kriteria', 'nilai'));
    }

    // Memperbarui data penilaian
    public function update(Request $request, $id)
    {
        $alternatif = DataAlternatif::findOrFail($id); // Pastikan data alternatif ada

        // Validasi input
        $request->validate([
            'subkriteria_1' => 'required|numeric',
            'subkriteria_2' => 'required|numeric',
            'subkriteria_3' => 'required|numeric',
            'subkriteria_4' => 'required|numeric',
            'subkriteria_5' => 'required|numeric',
            'subkriteria_6' => 'required|numeric',
        ]);

         // Lakukan update atau insert data penilaian untuk masing-masing kriteria
        foreach (range(1, 6) as $i) {
            $subkriteriaId = $request->input("subkriteria_$i"); // Ambil input subkriteria
            $subkriteria = Subkriteria::find($subkriteriaId); // Cari data subkriteria

            if ($subkriteria) {
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatif->id, // Berdasarkan alternatif dan kriteria
                        'kriteria_id' => $i,
                    ],
                    [
                        'subkriteria_id' => $subkriteria->id, // Simpan subkriteria yang dipilih
                        'nilai' => $subkriteria->nilai, // Ambil nilai dari subkriteria
                    ]
                );
            }
        }

        return redirect()->route('admin.penilaian.index')->with('success', 'Penilaian berhasil diperbarui!');
    }
}
