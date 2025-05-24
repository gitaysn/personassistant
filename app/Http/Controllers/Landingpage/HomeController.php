<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataAlternatif;
use App\Models\Kriteria;
use App\Models\QuizHistory;

class HomeController extends Controller
{
    public function index()
    {
        return view('landingpage.home', [
            'skorAkhir' => collect($this->getSkorAkhirByJenis('Dress'))->take(3)->values(),
            'blouseSkorAkhir' => collect($this->getSkorAkhirByJenis('Blouse'))->take(3)->values(),
            'cardiganSkorAkhir' => collect($this->getSkorAkhirByJenis('Cardigan'))->take(3)->values(),
            'rokSkorAkhir' => collect($this->getSkorAkhirByJenis('Rok'))->take(3)->values(),
            'celanaSkorAkhir' => collect($this->getSkorAkhirByJenis('Celana'))->take(3)->values(),
            'kriteria' => Kriteria::with('subkriteria')->get()
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

    /**
     * Fungsi untuk parsing rentang harga yang mempertimbangkan format dengan titik
     */
    private function parseHargaRange($hargaString)
    {
        // Menghilangkan spasi dan mengambil semua angka termasuk yang menggunakan titik sebagai pemisah ribuan
        $cleaned = str_replace([' ', '.'], ['', ''], $hargaString);
        
        // Mencari pola angka - angka
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $cleaned, $matches)) {
            return [
                'start' => (int) $matches[1],
                'end' => (int) $matches[2]
            ];
        }
        
        return null;
    }

    /**
     * Fungsi untuk menghitung similarity score antara preferensi user dan nilai alternatif
     */
    private function hitungSimilarityScore($preferensiValue, $alternatifValue, $kriteriaNama)
    {
        // Untuk kriteria harga, lakukan pengecekan range
        if (strtolower($kriteriaNama) === 'harga') {
            $preferensiRange = $this->parseHargaRange($preferensiValue);
            $alternatifRange = $this->parseHargaRange($alternatifValue);
            
            if ($preferensiRange && $alternatifRange) {
                // Hitung overlap range
                $overlapStart = max($preferensiRange['start'], $alternatifRange['start']);
                $overlapEnd = min($preferensiRange['end'], $alternatifRange['end']);
                
                if ($overlapStart <= $overlapEnd) {
                    // Ada overlap, hitung persentase overlap
                    $overlapSize = $overlapEnd - $overlapStart;
                    $preferensiSize = $preferensiRange['end'] - $preferensiRange['start'];
                    $similarity = $overlapSize / max($preferensiSize, 1);
                    return min(1.0, $similarity);
                } else {
                    // Tidak ada overlap, hitung kedekatan
                    $distance = min(
                        abs($preferensiRange['start'] - $alternatifRange['end']),
                        abs($preferensiRange['end'] - $alternatifRange['start'])
                    ) / max($preferensiRange['end'], $alternatifRange['end'], 1);
                    return max(0.1, 1 - $distance); // Minimal similarity 0.1
                }
            }
        }
        
        // Untuk kriteria lainnya, lakukan exact match atau partial match
        if (strtolower($preferensiValue) === strtolower($alternatifValue)) {
            return 1.0; // Perfect match
        }
        
        // Partial match berdasarkan similarity string
        $similarity = $this->calculateStringSimilarity($preferensiValue, $alternatifValue);
        return max(0.1, $similarity); // Minimal similarity 0.1
    }

    /**
     * Fungsi untuk menghitung similarity antara dua string
     */
    private function calculateStringSimilarity($str1, $str2)
    {
        $str1 = strtolower(trim($str1));
        $str2 = strtolower(trim($str2));
        
        // Jika sama persis
        if ($str1 === $str2) return 1.0;
        
        // Jika salah satu mengandung yang lain
        if (strpos($str1, $str2) !== false || strpos($str2, $str1) !== false) {
            return 0.8;
        }
        
        // Hitung Levenshtein distance untuk similarity
        $maxLen = max(strlen($str1), strlen($str2));
        if ($maxLen === 0) return 1.0;
        
        $distance = levenshtein($str1, $str2);
        $similarity = 1 - ($distance / $maxLen);
        
        return max(0.3, $similarity); // Minimal similarity 0.3
    }

    /**
     * Fungsi untuk mendapatkan nilai default berdasarkan preferensi user
     */
    private function getNilaiDefaultFromPreferensi($kriteriaNama, $preferensiValue, $maxNilai)
    {
        // Mapping tingkat preferensi ke nilai
        $preferenceMapping = [
            'sangat tinggi' => 0.9,
            'tinggi' => 0.8,
            'sedang' => 0.6,
            'rendah' => 0.4,
            'sangat rendah' => 0.2
        ];
        
        $lowerPreference = strtolower($preferensiValue);
        
        // Cek apakah ada mapping langsung
        foreach ($preferenceMapping as $key => $multiplier) {
            if (strpos($lowerPreference, $key) !== false) {
                return $maxNilai * $multiplier;
            }
        }
        
        // Default berdasarkan panjang string atau karakteristik lainnya
        $baseScore = min(strlen($preferensiValue) * 2, $maxNilai * 0.7);
        return max($maxNilai * 0.3, $baseScore); // Minimal 30% dari max nilai
    }

    private function getSkorAkhirByJenis($jenis, $preferensi = [])
{
    $mapping = [
        'jenis_acara' => 'Jenis Acara',
        'harga' => 'Harga',
        'jenis_pakaian' => 'Jenis Pakaian',
        'warna' => 'Warna Pakaian',
        'lokasi' => 'Lokasi Acara',
        'cuaca' => 'Cuaca Acara',
    ];

    // Ambil semua alternatif untuk jenis pakaian tertentu
    $alternatif = DataAlternatif::with(['penilaian.subkriteria.kriteria'])
        ->whereHas('penilaian.subkriteria.kriteria', fn($q) => $q->where('nama_kriteria', 'Jenis Pakaian'))
        ->whereHas('penilaian.subkriteria', fn($q) => $q->where('nama_subkriteria', $jenis))
        ->get();

    // FALLBACK: Jika tidak ada alternatif yang sesuai dengan jenis pakaian dari preferensi
    if ($alternatif->isEmpty()) {
        \Log::warning("Tidak ada alternatif untuk jenis pakaian: {$jenis}");
        
        // Ambil semua alternatif tanpa filter jenis pakaian spesifik
        $alternatif = DataAlternatif::with(['penilaian.subkriteria.kriteria'])->get();
        
        if ($alternatif->isEmpty()) {
            return [
                'data' => [],
                'message' => 'Tidak ada data alternatif dalam database',
                'fallback' => true
            ];
        }
    }

    $kriteria = Kriteria::with('subkriteria')->get();

    // Hitung nilai max dan min yang konsisten untuk normalisasi SAW
    $maxNilai = [];
    $minNilai = [];
    
    foreach ($kriteria as $k) {
        $nilaiKriteria = [];
        
        // Untuk SEMUA kriteria, ambil nilai dari alternatif yang sedang diproses
        foreach ($alternatif as $alt) {
            $penilaian = $alt->penilaian->where('kriteria_id', $k->id)->first();
            if ($penilaian && $penilaian->subkriteria && $penilaian->subkriteria->nilai > 0) {
                $nilaiKriteria[] = $penilaian->subkriteria->nilai;
            }
        }
        
        if (!empty($nilaiKriteria)) {
            $maxNilai[$k->id] = max($nilaiKriteria);
            $minNilai[$k->id] = min($nilaiKriteria);
        } else {
            // Fallback jika tidak ada nilai valid - ambil dari semua subkriteria untuk kriteria ini
            $subkriteriaNilai = $k->subkriteria->pluck('nilai')
                                              ->filter(function($val) { return $val > 0; })
                                              ->toArray();
            if (!empty($subkriteriaNilai)) {
                $maxNilai[$k->id] = max($subkriteriaNilai);
                $minNilai[$k->id] = min($subkriteriaNilai);
            } else {
                $maxNilai[$k->id] = 100; // Nilai default
                $minNilai[$k->id] = 1;
            }
        }
        
        \Log::info("=== DEBUG KRITERIA: {$k->nama_kriteria} ===");
        \Log::info("Jenis Pakaian: {$jenis}");
        \Log::info("Nilai ditemukan: " . json_encode($nilaiKriteria));
        \Log::info("Max: {$maxNilai[$k->id]}, Min: {$minNilai[$k->id]}");
    }

    // Buat matriks keputusan untuk ditampilkan
    $matriksKeputusan = [];
    $matriksNormalisasi = [];
    $matriksTerbobot = [];

    $hasil = $alternatif->map(function ($alt) use ($kriteria, $maxNilai, $minNilai, $preferensi, $mapping, &$matriksKeputusan, &$matriksNormalisasi, &$matriksTerbobot) {
        $totalSkor = 0;
        $detailSkor = [];
        $detailKriteria = [];
        $nilaiKeputusan = [];
        $nilaiNormalisasi = [];
        $nilaiTerbobot = [];
        $isUsingFallback = false;
        
        \Log::info("=== PERHITUNGAN UNTUK: {$alt->nama_alternatif} ===");
        
        foreach ($kriteria as $k) {
            $penilaian = $alt->penilaian->where('kriteria_id', $k->id)->first();
            $nilaiAsliDariKuesioner = $penilaian?->subkriteria->nilai ?? 0; // NILAI ASLI untuk matriks keputusan
            $namaSubkriteriaAlt = $penilaian?->subkriteria->nama_subkriteria ?? 'Tidak ada';

            // NILAI UNTUK PERHITUNGAN SAW (bisa dimodifikasi dengan fallback/similarity)
            $nilaiUntukPerhitungan = $nilaiAsliDariKuesioner;

            // FALLBACK: Jika tidak ada penilaian dari database, gunakan estimasi berdasarkan preferensi
            if (!$penilaian || $nilaiAsliDariKuesioner == 0) {
                $isUsingFallback = true;
                $preferensiKey = array_search($k->nama_kriteria, $mapping);
                
                if ($preferensiKey && isset($preferensi[$preferensiKey])) {
                    // Estimasi nilai berdasarkan preferensi user
                    $nilaiUntukPerhitungan = $this->getNilaiDefaultFromPreferensi($k->nama_kriteria, $preferensi[$preferensiKey], $maxNilai[$k->id]);
                    $nilaiAsliDariKuesioner = $nilaiUntukPerhitungan; // Untuk matriks keputusan juga menggunakan estimasi
                    $namaSubkriteriaAlt = $preferensi[$preferensiKey] . ' (estimasi)';
                    
                    \Log::info("FALLBACK - Menggunakan estimasi untuk {$k->nama_kriteria}: {$nilaiUntukPerhitungan}");
                } else {
                    // Jika tidak ada preferensi, gunakan nilai tengah
                    $nilaiUntukPerhitungan = ($maxNilai[$k->id] + $minNilai[$k->id]) / 2;
                    $nilaiAsliDariKuesioner = $nilaiUntukPerhitungan; // Untuk matriks keputusan juga
                    $namaSubkriteriaAlt = 'Nilai default (tengah)';
                    
                    \Log::info("FALLBACK - Menggunakan nilai default untuk {$k->nama_kriteria}: {$nilaiUntukPerhitungan}");
                }
            } else {
                // Jika ada data dari database, gunakan nilai asli untuk matriks keputusan
                // Tapi untuk perhitungan SAW, bisa ditingkatkan dengan similarity
                $preferensiKey = array_search($k->nama_kriteria, $mapping);
                if ($preferensiKey && isset($preferensi[$preferensiKey])) {
                    $similarityScore = $this->hitungSimilarityScore($preferensi[$preferensiKey], $namaSubkriteriaAlt, $k->nama_kriteria);
                    
                    // Boost nilai HANYA untuk perhitungan, BUKAN untuk matriks keputusan
                    $nilaiUntukPerhitungan = $nilaiAsliDariKuesioner * (0.5 + 0.5 * $similarityScore);
                    
                    \Log::info("SIMILARITY BOOST - {$k->nama_kriteria}: {$preferensi[$preferensiKey]} vs {$namaSubkriteriaAlt} = {$similarityScore}");
                    \Log::info("Nilai asli: {$nilaiAsliDariKuesioner}, Nilai untuk perhitungan: {$nilaiUntukPerhitungan}");
                }
            }

            // MATRIKS KEPUTUSAN = NILAI ASLI DARI DATABASE (TANPA MODIFIKASI APAPUN)
            // Kecuali jika memang tidak ada data sama sekali (fallback)
            $nilaiKeputusan[$k->nama_kriteria] = $nilaiAsliDariKuesioner;

            // PERBAIKAN NORMALISASI SAW - gunakan nilai asli untuk matriks normalisasi
            // Normalisasi harus konsisten menggunakan nilai yang sama dengan matriks keputusan
            $nilaiUntukNormalisasi = $nilaiAsliDariKuesioner;
            
            // Jika nilai kosong (fallback), gunakan nilai estimasi
            if ($nilaiUntukNormalisasi == 0) {
                $nilaiUntukNormalisasi = $nilaiUntukPerhitungan;
            }
            
            if (strtolower($k->jenis) === 'cost') {
                // KRITERIA COST (misal: Harga) - semakin rendah semakin baik
                if ($nilaiUntukNormalisasi > 0 && $minNilai[$k->id] > 0) {
                    $normalisasi = $minNilai[$k->id] / $nilaiUntukNormalisasi;
                } else {
                    $normalisasi = 0;
                }
            } else {
                // KRITERIA BENEFIT - semakin tinggi semakin baik  
                if ($maxNilai[$k->id] > 0 && $nilaiUntukNormalisasi > 0) {
                    $normalisasi = $nilaiUntukNormalisasi / $maxNilai[$k->id];
                } else {
                    $normalisasi = 0;
                }
            }

            // Pastikan normalisasi dalam rentang [0,1]
            $normalisasi = max(0, min(1, $normalisasi));

            // SKOR KRITERIA menggunakan nilai yang sudah di-boost untuk perhitungan final
            $nilaiUntukSkor = $nilaiUntukPerhitungan;
            
            if (strtolower($k->jenis) === 'cost') {
                if ($nilaiUntukSkor > 0 && $minNilai[$k->id] > 0) {
                    $normalisasiSkor = $minNilai[$k->id] / $nilaiUntukSkor;
                } else {
                    $normalisasiSkor = 0;
                }
            } else {
                if ($maxNilai[$k->id] > 0 && $nilaiUntukSkor > 0) {
                    $normalisasiSkor = $nilaiUntukSkor / $maxNilai[$k->id];
                } else {
                    $normalisasiSkor = 0;
                }
            }
            
            $normalisasiSkor = max(0, min(1, $normalisasiSkor));
            $skorKriteria = $normalisasiSkor * $k->bobot;
            $totalSkor += $skorKriteria;

            // Simpan untuk matriks - NORMALISASI BERDASARKAN NILAI ASLI
            $nilaiNormalisasi[$k->nama_kriteria] = $normalisasi;
            $nilaiTerbobot[$k->nama_kriteria] = $normalisasi * $k->bobot; // Terbobot juga pakai normalisasi nilai asli

            // Simpan detail untuk setiap kriteria
            $detailSkor[$k->nama_kriteria] = $skorKriteria; // Skor final tetap pakai yang di-boost
            $detailKriteria[$k->nama_kriteria] = [
                'nilai_alternatif' => $nilaiAsliDariKuesioner, // Tampilkan nilai asli
                'nilai_perhitungan' => $nilaiUntukPerhitungan, // Nilai yang dipakai untuk hitung
                'nama_subkriteria' => $namaSubkriteriaAlt,
                'bobot' => $k->bobot,
                'max_nilai' => $maxNilai[$k->id],
                'min_nilai' => $minNilai[$k->id],
                'normalisasi' => $normalisasi, // Normalisasi berdasarkan nilai asli
                'normalisasi_skor' => $normalisasiSkor, // Normalisasi untuk skor (yang di-boost)
                'skor_kriteria' => $skorKriteria,
                'jenis' => $k->jenis,
                'is_fallback' => $isUsingFallback
            ];
            
            \Log::info("Kriteria: {$k->nama_kriteria}");
            \Log::info("Nilai Asli (Matriks): {$nilaiAsliDariKuesioner}");
            \Log::info("Nilai Perhitungan: {$nilaiUntukPerhitungan}");
            \Log::info("Subkriteria: {$namaSubkriteriaAlt}");
            \Log::info("Normalisasi Matriks: {$normalisasi}");
            \Log::info("Normalisasi Skor: {$normalisasiSkor}");
            \Log::info("Skor: {$skorKriteria}");
            \Log::info("---");
        }

        // Simpan ke matriks - NILAI ASLI untuk keputusan
        $matriksKeputusan[$alt->nama_alternatif] = $nilaiKeputusan;
        $matriksNormalisasi[$alt->nama_alternatif] = array_map(function($val) {
            return round($val, 3);
        }, $nilaiNormalisasi);
        $matriksTerbobot[$alt->nama_alternatif] = array_map(function($val) {
            return round($val, 3);
        }, $nilaiTerbobot);

        $finalScore = round($totalSkor, 3);
        
        \Log::info("TOTAL SKOR FINAL {$alt->nama_alternatif}: {$finalScore}");
        \Log::info("================================");

        return [
            'id' => $alt->id,
            'nama' => $alt->nama_alternatif,
            'gambar' => $alt->gambar,
            'skor_total' => $finalScore,
            'detail_skor' => array_map(function($val) {
                return round($val, 3);
            }, $detailSkor),
            'detail_kriteria' => array_map(function($detail) {
                return [
                    'nilai_alternatif' => $detail['nilai_alternatif'], // Nilai asli
                    'nilai_perhitungan' => $detail['nilai_perhitungan'], // Nilai yang dipakai hitung
                    'nama_subkriteria' => $detail['nama_subkriteria'],
                    'bobot' => $detail['bobot'],
                    'max_nilai' => $detail['max_nilai'],
                    'min_nilai' => $detail['min_nilai'],
                    'normalisasi' => round($detail['normalisasi'], 3),
                    'normalisasi_skor' => round($detail['normalisasi_skor'], 3),
                    'skor_kriteria' => round($detail['skor_kriteria'], 3),
                    'jenis' => $detail['jenis'],
                    'is_fallback' => $detail['is_fallback'] ?? false
                ];
            }, $detailKriteria),
            'breakdown' => $this->generateBreakdownText($detailKriteria, $totalSkor, $isUsingFallback),
            'has_fallback' => $isUsingFallback
        ];
    })->sortByDesc('skor_total')->take(4)->values();

    $hasil = $hasil->all();
    
    return [
        'data' => $hasil,
        'matriks_keputusan' => $matriksKeputusan,
        'matriks_normalisasi' => $matriksNormalisasi,
        'matriks_terbobot' => $matriksTerbobot,
        'info_kriteria' => $kriteria->map(function($k) use ($maxNilai, $minNilai) {
            return [
                'nama' => $k->nama_kriteria,
                'bobot' => $k->bobot,
                'jenis' => $k->jenis,
                'max_nilai' => $maxNilai[$k->id],
                'min_nilai' => $minNilai[$k->id]
            ];
        })->keyBy('nama')->all(),
        'has_fallback_data' => collect($hasil)->contains('has_fallback', true),
        'preferensi_applied' => !empty($preferensi)
    ];
}

    /**
     * Generate breakdown text untuk menjelaskan perhitungan skor SAW
     */
    private function generateBreakdownText($detailKriteria, $totalSkor, $hasFallback = false)
    {
        $breakdown = "Perhitungan Skor SAW (Simple Additive Weighting):\n";
        $breakdown .= "================================================\n";
        
        if ($hasFallback) {
            $breakdown .= "⚠️  CATATAN: Beberapa nilai menggunakan estimasi karena data tidak tersedia di database\n\n";
        }
        
        foreach ($detailKriteria as $namaKriteria => $detail) {
            $breakdown .= "• {$namaKriteria}:\n";
            $breakdown .= "  - Nilai Produk: {$detail['nama_subkriteria']} ({$detail['nilai_alternatif']})";
            
            if ($detail['is_fallback'] ?? false) {
                $breakdown .= " [ESTIMASI]";
            }
            $breakdown .= "\n";
            
            $breakdown .= "  - Bobot Kriteria: {$detail['bobot']}\n";
            $breakdown .= "  - Jenis: {$detail['jenis']}\n";
            
            if (strtolower($detail['jenis']) === 'cost') {
                $breakdown .= "  - Normalisasi (Cost): {$detail['min_nilai']} / {$detail['nilai_alternatif']} = " . round($detail['normalisasi'], 3) . "\n";
                $breakdown .= "    (Semakin rendah nilai asli, semakin tinggi normalisasi - lebih baik)\n";
            } else {
                $breakdown .= "  - Normalisasi (Benefit): {$detail['nilai_alternatif']} / {$detail['max_nilai']} = " . round($detail['normalisasi'], 3) . "\n";
                $breakdown .= "    (Semakin tinggi nilai asli, semakin tinggi normalisasi - lebih baik)\n";
            }
            
            $breakdown .= "  - Skor: " . round($detail['normalisasi'], 3) . " × {$detail['bobot']} = " . round($detail['skor_kriteria'], 3) . "\n";
            $breakdown .= "\n";
        }
        
        $breakdown .= "Total Skor SAW: " . round($totalSkor, 3) . "\n";
        
        if ($hasFallback) {
            $breakdown .= "\n🔧 Metode Fallback yang Digunakan:\n";
            $breakdown .= "- Estimasi nilai berdasarkan preferensi user\n";
            $breakdown .= "- Similarity matching untuk data yang ada\n";
            $breakdown .= "- Nilai default untuk data yang tidak tersedia\n";
        }
        
        $breakdown .= "\nCatatan Metode SAW:\n";
        $breakdown .= "- Kriteria 'Cost': Harga - semakin rendah semakin baik\n";
        $breakdown .= "- Kriteria 'Benefit': Jenis Acara, Jenis Pakaian, Warna, Lokasi, Cuaca - semakin tinggi semakin baik\n";
        $breakdown .= "- Skor Final = Σ(Normalisasi × Bobot) untuk setiap kriteria\n";
        $breakdown .= "- Semakin tinggi skor total, semakin baik alternatif tersebut";
        
        return $breakdown;
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

        $preferensi = $request->only(['jenis_acara', 'harga', 'jenis_pakaian', 'warna', 'lokasi', 'cuaca']);
        $jenis = $preferensi['jenis_pakaian'] ?? 'Dress';

        $skorAkhir = $this->getSkorAkhirByJenis($jenis, $preferensi);

        // Ambil hasil rekomendasi dengan handling untuk fallback data
        $hasilRekomendasi = [];
        if (isset($skorAkhir['data']) && is_array($skorAkhir['data'])) {
            $hasilRekomendasi = array_slice(array_column($skorAkhir['data'], 'nama'), 0, 4);
        }

        // Tambahkan informasi fallback ke hasil rekomendasi jika ada
        $dataKuisioner = $request->except(['_token']);
        if ($skorAkhir['has_fallback_data'] ?? false) {
            $dataKuisioner['contains_fallback_data'] = true;
            $dataKuisioner['fallback_message'] = 'Beberapa rekomendasi menggunakan estimasi karena data tidak lengkap di database';
        }

        QuizHistory::create([
            'user_id' => auth()->id(),
            'skor_akhir' => json_encode($skorAkhir),
            'data_kuisioner' => json_encode($dataKuisioner),
            'hasil_rekomendasi' => json_encode($hasilRekomendasi),
        ]);
        
        return view('landingpage.rekomendasi', compact('skorAkhir', 'preferensi', 'jenis'));
    }
}