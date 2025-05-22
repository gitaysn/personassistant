<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataAlternatif;
use App\Models\Kriteria;
use App\Models\QuizHistory;
use App\Models\Subkriteria;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $skorAkhir = $this->getSkorAkhirByJenis('Dress');
        $blouseSkorAkhir = $this->getSkorAkhirByJenis('Blouse');
        $cardiganSkorAkhir = $this->getSkorAkhirByJenis('Cardigan');
        $rokSkorAkhir = $this->getSkorAkhirByJenis('Rok');
        $celanaSkorAkhir = $this->getSkorAkhirByJenis('Celana');

        $kriteria = Kriteria::with('subkriteria')->get();

        return view('landingpage.home', compact(
            'skorAkhir',
            'blouseSkorAkhir',
            'cardiganSkorAkhir',
            'rokSkorAkhir',
            'celanaSkorAkhir',
            'kriteria'
        ));
    }

    public function dress() { return $this->showJenisPakaian('Dress'); }
    public function blouse() { return $this->showJenisPakaian('Blouse'); }
    public function cardigan() { return $this->showJenisPakaian('Cardigan'); }
    public function rok() { return $this->showJenisPakaian('Rok'); }
    public function celana() { return $this->showJenisPakaian('Celana'); }

    private function showJenisPakaian($jenis, $preferensi = [])
    {
        $skorAkhir = $this->getSkorAkhirByJenis($jenis, $preferensi);

        $alternatif = DataAlternatif::whereHas('penilaian.subkriteria', function ($query) use ($jenis) {
            $query->where('nama_subkriteria', $jenis);
        })->get();

        $kriteria = Kriteria::with('subkriteria')->get();

        return view('landingpage.rekomendasi', compact(
            'alternatif',
            'kriteria',
            'jenis',
            'skorAkhir',
            'preferensi'
        ));
    }

    private function getSkorAkhirByJenis($jenis, $preferensi = [], $withPreferensiBonus = false)
{
    $mapping = [
        'jenis_acara' => 'Jenis Acara',
        'harga' => 'Harga',
        'jenis_pakaian' => 'Jenis Pakaian',
        'warna' => 'Warna Pakaian',
        'lokasi' => 'Lokasi Acara',
        'cuaca' => 'Cuaca Acara',
    ];

    \Log::info('Preferensi diterima: ', $preferensi);

    $baseAlternatif = DataAlternatif::with(['penilaian.subkriteria.kriteria'])
        ->whereHas('penilaian.subkriteria.kriteria', fn($q) => $q->where('nama_kriteria', 'Jenis Pakaian'))
        ->whereHas('penilaian.subkriteria', fn($q) => $q->where('nama_subkriteria', $jenis))
        ->get();

    if ($baseAlternatif->isEmpty()) return [];

    $kriteria = Kriteria::with('subkriteria')->get();

    // Ambil nilai preferensi user sebagai bobot pembanding
    $userPreferensi = [];
    foreach ($preferensi as $key => $val) {
        $namaKriteria = $mapping[$key] ?? null;
        if ($namaKriteria) {
            $kriteriaModel = $kriteria->firstWhere('nama_kriteria', $namaKriteria);
            if ($kriteriaModel) {
                $sub = $kriteriaModel->subkriteria->firstWhere('nama_subkriteria', $val);
                if ($sub) {
                    $userPreferensi[$kriteriaModel->id] = $sub->nilai;
                }
            }
        }
    }

    // Hitung nilai max dan min
    $maxNilai = [];
    $minNilai = [];
    foreach ($kriteria as $i => $k) {
        $nilai = $baseAlternatif->map(fn($alt) =>
            $alt->penilaian->firstWhere('kriteria_id', $k->id)?->subkriteria->nilai
        )->filter();
        $maxNilai[$k->id] = $nilai->max() ?? 1;
        $minNilai[$k->id] = $nilai->min() ?? 1;
    }

    // Hitung skor berdasarkan selisih preferensi user vs alternatif
    $hasilSAW = $baseAlternatif->map(function ($alt) use ($kriteria, $maxNilai, $minNilai, $userPreferensi) {
        $totalSkor = 0;
        $detail = [];

        foreach ($kriteria as $k) {
            $pn = $alt->penilaian->firstWhere('kriteria_id', $k->id);
            $nilaiAlt = $pn->subkriteria->nilai ?? 0;
            $nilaiUser = $userPreferensi[$k->id] ?? 0;

            if (strtolower($k->jenis) === 'cost') {
                $normalAlt = $nilaiAlt > 0 ? $minNilai[$k->id] / $nilaiAlt : 0;
                $normalUser = $nilaiUser > 0 ? $minNilai[$k->id] / $nilaiUser : 0;
            } else {
                $normalAlt = $maxNilai[$k->id] > 0 ? $nilaiAlt / $maxNilai[$k->id] : 0;
                $normalUser = $maxNilai[$k->id] > 0 ? $nilaiUser / $maxNilai[$k->id] : 0;
            }

            $selisih = abs($normalAlt - $normalUser);
            $bobot = round((1 - $selisih) * $k->bobot, 4); // semakin kecil selisih, semakin besar skor

            $totalSkor += $bobot;

            $detail[] = [
                'kriteria' => $k->nama_kriteria,
                'alt_val' => $nilaiAlt,
                'user_val' => $nilaiUser,
                'selisih' => $selisih,
                'bobot_kriteria' => $k->bobot,
                'skor_kriteria' => $bobot,
            ];
        }

        return [
            'id' => $alt->id,
            'nama' => $alt->nama_alternatif,
            'gambar' => $alt->gambar,
            'skor_total' => round($totalSkor, 4),
            'skor_saw' => round($totalSkor, 4), // supaya blade tidak error
            'detail_normalisasi' => $detail
        ];
    });

    return $hasilSAW->sortByDesc('skor_total')->values()->all();
}


    public function prosesRekomendasi(Request $request)
    {
        $jenisPakaian = $request->input('jenis_pakaian');
        return $this->showJenisPakaian($jenisPakaian);
    }

    public function simpanKuisionerDanRekomendasi(Request $request)
    {
        $request->validate([
            'jenis_acara' => 'required',
            'harga' => 'required',
            'jenis_pakaian' => 'required',
            'warna' => 'required',
            'lokasi' => 'required',
            'cuaca' => 'required',
        ]);

        $preferensi = $request->only([
            'jenis_acara',
            'harga',
            'jenis_pakaian',
            'warna',
            'lokasi',
            'cuaca'
        ]);

        $jenis = $preferensi['jenis_pakaian'] ?? 'Dress';
        $skorAkhir = $this->getSkorAkhirByJenis($jenis, $preferensi, true);

        // ⬇️ SIMPAN ke quiz_histories (tanpa login)
        $skorAkhir = $this->getSkorAkhirByJenis($jenis, $preferensi, true);
        $top3 = collect($skorAkhir)->take(3)->map(function ($alt) {
            return [
                'nama' => $alt['nama'],
                'skor' => $alt['skor_total'],
                'gambar' => $alt['gambar'] ?? null
            ];
        })->values()->all();

        $this->simpanRiwayatKuisioner($preferensi, $skorAkhir);

        $alternatif = DataAlternatif::whereHas('penilaian.subkriteria', function ($query) use ($jenis) {
            $query->where('nama_subkriteria', $jenis);
        })->get();

        $kriteria = Kriteria::with('subkriteria')->get();

        return view('landingpage.rekomendasi', compact(
            'alternatif',
            'kriteria',
            'jenis',
            'skorAkhir',
            'preferensi'
        ));
    }

    private function simpanRiwayatKuisioner($preferensi, $hasilRekomendasi = [])
    {
        try {
            QuizHistory::create([
                'data_kuisioner' => json_encode($preferensi),
                'hasil_rekomendasi' => json_encode($hasilRekomendasi),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::info('Gagal menyimpan riwayat kuisioner: ' . $e->getMessage());
        }
    }

    public function getKriteria()
    {
        $kriteria = Kriteria::with('subkriteria')->get();
        return response()->json($kriteria);
    }
}
