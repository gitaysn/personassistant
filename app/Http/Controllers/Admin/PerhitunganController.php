<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataAlternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Subkriteria;

class PerhitunganController extends Controller
{
    // Method untuk masing-masing jenis pakaian
    public function dress()    { return $this->showJenisPakaian('Dress'); }
    public function blouse()   { return $this->showJenisPakaian('Blouse'); }
    public function cardigan() { return $this->showJenisPakaian('Cardigan'); }
    public function rok()      { return $this->showJenisPakaian('Rok'); }
    public function celana()   { return $this->showJenisPakaian('Celana'); }

    // Method umum untuk menampilkan data berdasarkan jenis pakaian
    public function showJenisPakaian($jenis)
    {
        // Ambil data alternatif yang memiliki penilaian pada kriteria ke-3 (jenis pakaian),
        // dan nilai subkriteria-nya sesuai dengan jenis yang dipilih (misalnya 'Dress')
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

        // Hitung nilai maksimum dan minimum untuk tiap kriteria
        $maxNilai = [];
        $minNilai = [];

        // Loop setiap kriteria untuk mencari nilai max & min dari semua alternatif
        foreach ($kriteria as $index => $k) {
            $nilaiKriteria = [];

            foreach ($alternatif as $alt) {
                // Ambil penilaian alternatif berdasarkan kriteria
                $penilaian = $alt->penilaian->firstWhere('kriteria_id', $k->id);
                if ($penilaian && $penilaian->subkriteria) {
                    $nilaiKriteria[] = $penilaian->subkriteria->nilai;
                }
            }

            $kode = 'C' . ($index + 1); // Buat kode seperti C1, C2, dst
            $maxNilai[$kode] = count($nilaiKriteria) > 0 ? max($nilaiKriteria) : 1;
            $minNilai[$kode] = count($nilaiKriteria) > 0 ? min($nilaiKriteria) : 1;
        }

        $normalisasi = []; // Menyimpan hasil normalisasi setiap alternatif
        $pembobotan = []; // Menyimpan hasil pembobotan setiap alternatif

        // Proses normalisasi dan pembobotan
        foreach ($alternatif as $alt) {
            $barisNormal = []; // Normalisasi untuk satu alternatif
            $barisBobot = []; // Bobot untuk satu alternatif

        foreach ($kriteria as $index => $k) {
            $penilaian = $alt->penilaian->firstWhere('kriteria_id', $k->id);
            $nilai = $penilaian && $penilaian->subkriteria ? $penilaian->subkriteria->nilai : 0;

            $kode = 'C' . ($index + 1);

            // Normalisasi berdasarkan jenis kriteria: Benefit atau Cost
            if ($k->jenis == 'Cost') {
                $normal = $nilai > 0 ? $minNilai[$kode] / $nilai : 0;
            } else {
                $normal = $maxNilai[$kode] > 0 ? $nilai / $maxNilai[$kode] : 0;
            }

            // Simpan yang sudah dibulatkan ke 3 digit
            $normalRounded = round($normal, 3);

            // Hitung bobot dengan bobot kriteria
            $bobot = round($normalRounded * $k->bobot, 3);

            $barisNormal[$kode] = $normalRounded;
            $barisBobot[$kode] = $bobot;
        }

         // Simpan hasil normalisasi dan bobot dari satu alternatif
        $normalisasi[] = [
            'nama' => $alt->nama_alternatif,
            'nilai' => $barisNormal
        ];

        $pembobotan[] = [
            'nama' => $alt->nama_alternatif,
            'nilai' => $barisBobot
        ];
    }

        // Hitung skor akhir
        $skorAkhir = [];

        // Hitung skor akhir dengan menjumlahkan semua nilai bobot
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

        $viewName = strtolower($jenis); // Konversi nama jenis menjadi lowercase untuk nama file view
        return view("admin.pages.perhitungan.$viewName", compact(
            'alternatif',
            'kriteria',
            'jenis',
            'normalisasi',
            'pembobotan',
            'skorAkhir'
        ));
    }
}