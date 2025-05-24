<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataAlternatif;
use App\Models\Kriteria;
use App\Models\QuizHistory;
use App\Models\Subkriteria;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::with('subkriteria')->get();

        return view('landingpage.home', [
            'skorAkhir' => $this->getSkorAkhirByJenis('Dress'),
            'blouseSkorAkhir' => $this->getSkorAkhirByJenis('Blouse'),
            'cardiganSkorAkhir' => $this->getSkorAkhirByJenis('Cardigan'),
            'rokSkorAkhir' => $this->getSkorAkhirByJenis('Rok'),
            'celanaSkorAkhir' => $this->getSkorAkhirByJenis('Celana'),
            'kriteria' => $kriteria
        ]);
    }

    public function dress() { return $this->showJenisPakaian('Dress'); }
    public function blouse() { return $this->showJenisPakaian('Blouse'); }
    public function cardigan() { return $this->showJenisPakaian('Cardigan'); }
    public function rok() { return $this->showJenisPakaian('Rok'); }
    public function celana() { return $this->showJenisPakaian('Celana'); }

    private function showJenisPakaian($jenis, $preferensi = [])
    {
        $skorAkhir = $this->getSkorAkhirByJenis($jenis, $preferensi);
        $alternatif = DataAlternatif::whereHas('penilaian.subkriteria', fn($q) => $q->where('nama_subkriteria', $jenis))->get();
        $kriteria = Kriteria::with('subkriteria')->get();

        return view('landingpage.rekomendasi', compact('alternatif', 'kriteria', 'jenis', 'skorAkhir', 'preferensi'));
    }

    private function getSkorAkhirByJenis($jenis, $preferensi = [])
    {
        $alternatif = DataAlternatif::with(['penilaian.subkriteria', 'penilaian.kriteria'])
            ->whereHas('penilaian.subkriteria', function ($query) use ($jenis) {
                $query->where('nama_subkriteria', $jenis);
            })
            ->get();

        $kriteria = Kriteria::with('subkriteria')->get();
        $skorAkhir = [];

        foreach ($alternatif as $alt) {
            $total = 0;
            foreach ($kriteria as $k) {
                $penilaian = $alt->penilaian->where('kriteria_id', $k->id)->first();
                if (!$penilaian) continue;

                $nilaiAlt = $penilaian->subkriteria->nilai;
                $nilaiPref = $preferensi[$this->mapKriteriaKey($k->nama_kriteria)]['nilai'] ?? $nilaiAlt;

                $normalisasi = 1 - abs($nilaiAlt - $nilaiPref);
                $total += $normalisasi * $k->bobot;
            }

            $skorAkhir[] = [
                'alternatif' => $alt,
                'skor' => round($total, 3),
            ];
        }

        usort($skorAkhir, fn($a, $b) => $b['skor'] <=> $a['skor']);

        return $skorAkhir;
    }

    private function mapKriteriaKey($nama)
    {
        $nama = strtolower($nama);
        return match ($nama) {
            'harga', 'cost' => 'harga',
            'jenis pakaian' => 'jenis',
            'warna pakaian' => 'warna',
            'lokasi acara' => 'lokasi',
            'cuaca acara' => 'cuaca',
            'jenis acara' => 'acara',
            default => str_replace(' ', '_', $nama),
        };
    }

    public function simpankuisionerdanrekomendasi(Request $request)
    {
        try {
            $validated = $request->validate([
                'jenis_acara' => 'required|string',
                'harga' => 'required|string',
                'jenis_pakaian' => 'required|string',
                'warna' => 'required|string',
                'cuaca' => 'required|string',
                'lokasi' => 'required|string',
            ]);

            // Kumpulkan preferensi untuk diproses
            $jawaban = [
                'jenis_acara' => $validated['jenis_acara'],
                'harga' => $validated['harga'],
                'warna' => $validated['warna'],
                'cuaca' => $validated['cuaca'],
                'lokasi' => $validated['lokasi'],
            ];

            $preferensi = $this->prosesPreferensiBerdasarkanSubkriteria($jawaban);
            $skorAkhir = $this->getSkorAkhirByJenis($validated['jenis_pakaian'], $preferensi);

            // Simpan ke database
            $quizHistory = new QuizHistory();
            $quizHistory->data_kuisioner = json_encode($jawaban);
            $quizHistory->hasil_rekomendasi = json_encode($skorAkhir);
            $quizHistory->save();

            return $this->showJenisPakaian($validated['jenis_pakaian'], $preferensi);

        } catch (\Exception $e) {
            Log::error('Error simpan kuis: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    private function prosesPreferensiBerdasarkanSubkriteria($jawaban)
    {
        $preferensi = [];

        foreach ($jawaban as $nama_kriteria => $nama_subkriteria) {
            $kriteria = Kriteria::where('nama_kriteria', $nama_kriteria)->first();
            $subkriteria = Subkriteria::where('nama_subkriteria', $nama_subkriteria)
                                    ->where('kriteria_id', $kriteria->id ?? null)
                                    ->first();

            if ($kriteria && $subkriteria) {
                $key = $this->mapKriteriaKey($kriteria->nama_kriteria);

                $preferensi[$key] = [
                    'value' => $subkriteria->nama_subkriteria,
                    'nilai' => $subkriteria->nilai,
                    'bobot' => $kriteria->bobot
                ];

                if ($key === 'harga') {
                    $preferensi[$key]['range'] = $subkriteria->nama_subkriteria;
                }
            }
        }

        return $preferensi;
    }
    

    // Menampilkan form kuis
    public function showKuis($jenis = 'Dress')
    {
        $kriteria = Kriteria::with(['subkriteria' => function($query) {
            $query->orderBy('nilai', 'desc');
        }])->get();

        return view('landingpage.pilihpakaian', compact('kriteria', 'jenis'));
    }
}
