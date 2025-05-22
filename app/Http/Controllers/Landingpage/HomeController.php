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

        $baseAlternatif = DataAlternatif::with(['penilaian.subkriteria.kriteria'])
            ->whereHas('penilaian.subkriteria.kriteria', fn($q) => $q->where('nama_kriteria', 'Jenis Pakaian'))
            ->whereHas('penilaian.subkriteria', fn($q) => $q->where('nama_subkriteria', $jenis))
            ->get();

        if ($baseAlternatif->isEmpty()) return [];

        $kriteria = Kriteria::with('subkriteria')->get();

        // Ambil preferensi user sebagai angka
        $userPrefNilai = [];
        foreach ($preferensi as $key => $val) {
            $namaKriteria = $mapping[$key] ?? null;
            if (!$namaKriteria) continue;

            $krit = $kriteria->firstWhere('nama_kriteria', $namaKriteria);
            if ($krit) {
                $sub = Subkriteria::where('nama_subkriteria', $val)
                    ->where('kriteria_id', $krit->id)->first();
                if ($sub) {
                    $userPrefNilai[$krit->id] = $sub->nilai;
                }
            }
        }

        // Hitung skor SAW berdasarkan selisih preferensi
        $hasil = $baseAlternatif->map(function ($alt) use ($kriteria, $userPrefNilai) {
            $totalSkor = 0;
            foreach ($kriteria as $k) {
                $pen = $alt->penilaian->firstWhere('kriteria_id', $k->id);
                $nilaiAlt = $pen->subkriteria->nilai ?? 0;
                $nilaiUser = $userPrefNilai[$k->id] ?? $nilaiAlt;

                if (strtolower($k->jenis) === 'cost') {
                    $normalAlt = $nilaiAlt > 0 ? 1 / $nilaiAlt : 0;
                    $normalUser = $nilaiUser > 0 ? 1 / $nilaiUser : 0;
                } else {
                    $normalAlt = $nilaiAlt;
                    $normalUser = $nilaiUser;
                }

                $selisih = abs($normalAlt - $normalUser);
                $totalSkor += (1 - $selisih) * $k->bobot; // semakin dekat preferensi, semakin tinggi skor
            }

            return [
                'id' => $alt->id,
                'nama' => $alt->nama_alternatif,
                'gambar' => $alt->gambar,
                'skor_saw' => round($totalSkor, 4)
            ];
        });

        return $hasil->sortByDesc('skor_saw')->values()->all();
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

        $this->simpanRiwayatKuisioner($preferensi);

        $jenis = $preferensi['jenis_pakaian'] ?? 'Dress';
        $skorAkhir = $this->getSkorAkhirByJenis($jenis, $preferensi, true);

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

    private function simpanRiwayatKuisioner($preferensi)
    {
        try {
            QuizHistory::create([
                'user_preferences' => json_encode($preferensi),
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
