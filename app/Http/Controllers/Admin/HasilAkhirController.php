<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataAlternatif;
use App\Models\Subkriteria;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Barryvdh\DomPDF\Facade\Pdf;


class HasilAkhirController extends Controller
{
    public function dress()
    {
        return $this->showJenisPakaian('Dress');
    }

    public function blouse()
    {
        return $this->showJenisPakaian('Blouse');
    }

    public function cardigan()
    {
        return $this->showJenisPakaian('Cardigan');
    }

    public function rok()
    {
        return $this->showJenisPakaian('Rok');
    }

    public function celana()
    {
        return $this->showJenisPakaian('Celana');
    }

    // Method umum untuk menampilkan data berdasarkan jenis pakaian
    private function showJenisPakaian($jenis)
    {
        // Ambil data alternatif yang memiliki penilaian pada kriteria jenis pakaian
        $alternatif = DataAlternatif::with(['penilaian.subkriteria'])
            ->whereHas('penilaian', function($query) use ($jenis) {
                $query->where('kriteria_id', 3)
                    ->whereHas('subkriteria', function($subquery) use ($jenis) {
                        $subquery->where('nama_subkriteria', $jenis);
                    });
            })
            ->get();
        
        // Ambil semua data kriteria
        $kriteria = Kriteria::all();

        // Menentukan nilai maksimum dan minimum dari masing-masing kriteria
        $maxNilai = [];
        $minNilai = [];

        foreach ($kriteria as $index => $k) {
            $nilaiKriteria = [];

            foreach ($alternatif as $alt) {
                $penilaian = $alt->penilaian->firstWhere('kriteria_id', $k->id);
                if ($penilaian && $penilaian->subkriteria) {
                    $nilaiKriteria[] = $penilaian->subkriteria->nilai;
                }
            }

            $kode = 'C' . ($index + 1);
            $maxNilai[$kode] = count($nilaiKriteria) > 0 ? max($nilaiKriteria) : 1;
            $minNilai[$kode] = count($nilaiKriteria) > 0 ? min($nilaiKriteria) : 1;
        }

        // Hitung normalisasi dan pembobotan
        $normalisasi = [];
        $pembobotan = [];

        foreach ($alternatif as $alt) {
            $barisNormal = [];
            $barisBobot = [];

            foreach ($kriteria as $index => $k) {
                $penilaian = $alt->penilaian->firstWhere('kriteria_id', $k->id);
                $nilai = $penilaian && $penilaian->subkriteria ? $penilaian->subkriteria->nilai : 0;

                $kode = 'C' . ($index + 1);

                 // Normalisasi berdasarkan jenis kriteria (Benefit atau Cost)
                if ($k->jenis == 'Cost') {
                    $normal = $nilai > 0 ? round($minNilai[$kode] / $nilai, 3) : 0;
                } else {
                    $normal = $maxNilai[$kode] > 0 ? round($nilai / $maxNilai[$kode], 3) : 0;
                }

                // Pembobotan = normalisasi * bobot kriteria
                $bobot = round($normal * $k->bobot, 3);

                $barisNormal[$kode] = $normal;
                $barisBobot[$kode] = $bobot;
            }

            $normalisasi[] = [
                'nama' => $alt->nama_alternatif,
                'nilai' => $barisNormal
            ];

            $pembobotan[] = [
                'nama' => $alt->nama_alternatif,
                'nilai' => $barisBobot
            ];
        }

         // Hitung skor akhir dari hasil pembobotan
        $skorAkhir = [];

        foreach ($pembobotan as $item) {
            $skor = array_sum($item['nilai']);
            $skorAkhir[] = [
                'nama' => $item['nama'],
                'skor' => round($skor, 3)
            ];
        }

        // Urutkan dari skor tertinggi
        usort($skorAkhir, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return view("admin.pages.hasil." . strtolower($jenis), compact(
            'alternatif',
            'kriteria',
            'jenis',
            'normalisasi',
            'pembobotan',
            'skorAkhir'
        ));
    }

    public function exportPDF($jenis)
{
    // Ambil data sesuai jenis pakaian
    $alternatif = DataAlternatif::with(['penilaian.subkriteria'])
        ->whereHas('penilaian', function($query) use ($jenis) {
            $query->where('kriteria_id', 3)
                ->whereHas('subkriteria', function($subquery) use ($jenis) {
                    $subquery->where('nama_subkriteria', $jenis);
                });
        })
        ->get();

    // Ambil semua kriteria
    $kriteria = Kriteria::all();

    // Hitung nilai maksimum dan minimum
    $maxNilai = [];
    $minNilai = [];

    foreach ($kriteria as $index => $k) {
        $nilaiKriteria = [];

        foreach ($alternatif as $alt) {
            $penilaian = $alt->penilaian->firstWhere('kriteria_id', $k->id);
            if ($penilaian && $penilaian->subkriteria) {
                $nilaiKriteria[] = $penilaian->subkriteria->nilai;
            }
        }

        $kode = 'C' . ($index + 1);
        $maxNilai[$kode] = count($nilaiKriteria) > 0 ? max($nilaiKriteria) : 1;
        $minNilai[$kode] = count($nilaiKriteria) > 0 ? min($nilaiKriteria) : 1;
    }

    // Hitung pembobotan
    $pembobotan = [];

    foreach ($alternatif as $alt) {
        $barisBobot = [];

        foreach ($kriteria as $index => $k) {
            $penilaian = $alt->penilaian->firstWhere('kriteria_id', $k->id);
            $nilai = $penilaian && $penilaian->subkriteria ? $penilaian->subkriteria->nilai : 0;

            $kode = 'C' . ($index + 1);

            if ($k->jenis == 'Cost') {
                $normal = $nilai > 0 ? round($minNilai[$kode] / $nilai, 3) : 0;
            } else {
                $normal = $maxNilai[$kode] > 0 ? round($nilai / $maxNilai[$kode], 3) : 0;
            }

            $bobot = round($normal * $k->bobot, 3);

            $barisBobot[$kode] = $bobot;
        }

        $pembobotan[] = [
            'nama' => $alt->nama_alternatif,
            'nilai' => $barisBobot
        ];
    }

    // Hitung skor akhir
    $skorAkhir = [];

    foreach ($pembobotan as $item) {
        $skor = array_sum($item['nilai']);
        $skorAkhir[] = [
            'nama' => $item['nama'],
            'skor' => round($skor, 3)
        ];
    }

    // Urutkan skor dari yang tertinggi
    usort($skorAkhir, function ($a, $b) {
        return $b['skor'] <=> $a['skor'];
    });

    // Buat file PDF dari view dan data skor akhir
    $pdf = Pdf::loadView('admin.pages.hasil.pdf', compact('jenis', 'skorAkhir'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('hasil_akhir_' . strtolower($jenis) . '.pdf');
}

    
}
