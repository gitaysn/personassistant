<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Admin\PerhitunganController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataAlternatif;
use App\Models\Kriteria;

class HomeController extends Controller
{
    public function index()
    {
        $perhitunganController = new PerhitunganController();

        $dressRecommendation = $perhitunganController->showJenisPakaian('Dress');
        $skorAkhir = $dressRecommendation->getData()['skorAkhir'];

        $blouseRecommendation = $perhitunganController->showJenisPakaian('Blouse');
        $blouseSkorAkhir = $blouseRecommendation->getData()['skorAkhir'];

        $cardiganRecommendation = $perhitunganController->showJenisPakaian('Cardigan');
        $cardiganSkorAkhir = $cardiganRecommendation->getData()['skorAkhir'];

        $rokRecommendation = $perhitunganController->showJenisPakaian('Rok');
        $rokSkorAkhir = $rokRecommendation->getData()['skorAkhir'];

        $celanaRecommendation = $perhitunganController->showJenisPakaian('Celana');
        $celanaSkorAkhir = $celanaRecommendation->getData()['skorAkhir'];

        return view('landingpage.home', compact('skorAkhir', 'blouseSkorAkhir', 'cardiganSkorAkhir', 'rokSkorAkhir', 'celanaSkorAkhir'));
    }

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

    private function showJenisPakaian($jenis)
    {
        // Memastikan $jenis adalah array dan filter menggunakan whereIn
        $alternatif = DataAlternatif::with(['penilaian.subkriteria'])
            ->whereHas('penilaian', function($query) use ($jenis) {
                $query->where('kriteria_id', 3)
                    ->whereHas('subkriteria', function($subquery) use ($jenis) {
                        // Memfilter subkriteria berdasarkan nama yang ada dalam $jenis (bisa lebih dari satu)
                        $subquery->whereIn('nama_subkriteria', [$jenis]); // Menggunakan whereIn untuk array
                    });
            })
            ->get();

        $kriteria = Kriteria::all();

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

        $normalisasi = [];
        $pembobotan = [];

        foreach ($alternatif as $alt) {
            $barisNormal = [];
            $barisBobot = [];

            foreach ($kriteria as $index => $k) {
                $penilaian = $alt->penilaian->firstWhere('kriteria_id', $k->id);
                $nilai = $penilaian && $penilaian->subkriteria ? $penilaian->subkriteria->nilai : 0;

                $kode = 'C' . ($index + 1);

                $normal = ($k->jenis == 'Cost')
                    ? ($nilai > 0 ? round($minNilai[$kode] / $nilai, 3) : 0)
                    : ($maxNilai[$kode] > 0 ? round($nilai / $maxNilai[$kode], 3) : 0);

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

        $skorAkhir = [];

        foreach ($pembobotan as $item) {
            $skor = array_sum($item['nilai']);

            // Cari gambar dari alternatif yang sesuai
            $alt = $alternatif->firstWhere('nama_alternatif', $item['nama']);
            $gambar = $alt ? $alt->gambar : null;

            // Pastikan skor adalah angka atau string, bukan array
            $skorAkhir[] = [
                'nama' => $item['nama'],  // pastikan ini string
                'skor' => round($skor, 3),  // pastikan ini angka, bukan array
                'gambar' => $gambar,
            ];
        }

        // Urutkan skorAkhir
        usort($skorAkhir, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        // Kirim data ke tampilan
        return view('landingpage.rekomendasi', compact(
            'alternatif',
            'kriteria',
            'jenis',
            'normalisasi',
            'pembobotan',
            'skorAkhir'
        ));
        }

    // ✅ Tambahan method prosesRekomendasi
    public function prosesRekomendasi(Request $request)
    {
        // Tangkap jenis pakaian sebagai array
        $jenisPakaian = $request->input('jenis_pakaian'); // Ini akan menangkap data array

        // Pastikan mengirim data ke metode yang sesuai
        return $this->showJenisPakaian($jenisPakaian);
    }

}
