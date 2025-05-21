<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataAlternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Subkriteria;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexPage()
    {
        // Ambil jumlah data dari masing-masing model
        $totalKriteria = Kriteria::count();
        $totalSubKriteria = Subkriteria::count();
        $totalAlternatif = DataAlternatif::count();
        $totalPenilaian = Penilaian::count();

        // Ambil salah satu kriteria, misal yang pertama
        $kriteriaPertama = Kriteria::first();

        // Kirim data ke view
        return view('admin.pages.dashboard.index', [
            'totalKriteria' => $totalKriteria,
            'totalSubKriteria' => $totalSubKriteria,
            'totalAlternatif' => $totalAlternatif,
            'totalPenilaian' => $totalPenilaian,
            'totalPerhitungan' => $totalPenilaian,
            'totalHasilAkhir' => $totalPenilaian,
            'kriteriaId' => $kriteriaPertama ? $kriteriaPertama->id : null,
        ]);
    }
}
