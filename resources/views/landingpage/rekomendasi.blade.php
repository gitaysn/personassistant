<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi {{ $jenis ?? 'Pakaian' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .hover-zoom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .progress-bar {
            background: linear-gradient(90deg, #ef4444, #f97316, #eab308, #22c55e);
            height: 6px;
            border-radius: 3px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .kriteria-scroll {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
        .kriteria-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .kriteria-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        .kriteria-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .kriteria-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .home-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
            transition: all 0.3s ease;
        }
        .home-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        @media (max-width: 640px) {
            .home-button {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Home Button - Fixed position for desktop, relative for mobile -->
    <div class="sm:fixed sm:top-5 sm:left-5 sm:z-50 container mx-auto px-4 pt-4 sm:p-0">
        <a href="/" class="home-button inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg shadow-md border font-medium transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="hidden sm:inline">Kembali ke Beranda</span>
            <span class="sm:hidden">Home</span>
        </a>
    </div>

    <div class="container mx-auto px-4 py-8 sm:pt-20">
        <h1 class="text-2xl font-bold mb-6 text-center">Rekomendasi {{ $jenis ?? 'Pakaian' }}</h1>

        @if (!empty($skorAkhir) && (isset($skorAkhir['data']) ? count($skorAkhir['data']) > 0 : count($skorAkhir) > 0))
            
            <!-- Navigation Buttons -->
            <div class="mb-6 text-center space-y-3 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center sm:items-center">
                <button onclick="toggleMatriks()" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition-colors w-full sm:w-auto">
                    📊 Lihat Matriks Keputusan SAW
                </button>
            </div>

            <!-- Section Matriks Keputusan (Hidden by default) -->
            <div id="matriksSection" class="hidden mb-8 bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Matriks Keputusan SAW</h2>
                
                <!-- Info Kriteria - Layout Horizontal -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold mb-3 text-gray-800">Informasi Kriteria:</h3>
                    <div class="kriteria-scroll">
                        <div id="infoKriteria" class="flex gap-4 min-w-max">
                            <!-- Akan diisi via JavaScript -->
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 mt-2 text-center">
                        💡 Geser horizontal untuk melihat semua kriteria
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="flex border-b mb-4 overflow-x-auto">
                    <button onclick="showMatriks('keputusan')" id="tabKeputusan" class="px-4 py-2 font-medium border-b-2 border-blue-500 text-blue-600 whitespace-nowrap">
                        1. Matriks Keputusan (X)
                    </button>
                    <button onclick="showMatriks('normalisasi')" id="tabNormalisasi" class="px-4 py-2 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        2. Matriks Normalisasi (R)
                    </button>
                    <button onclick="showMatriks('terbobot')" id="tabTerbobot" class="px-4 py-2 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        3. Matriks Terbobot (V)
                    </button>
                </div>

                <!-- Matriks Tables -->
                <div id="matriksKeputusan" class="matriks-content overflow-x-auto">
                    <h3 class="font-semibold mb-2">Matriks Keputusan (X)</h3>
                    <p class="text-sm text-gray-600 mb-3">Nilai asli dari setiap alternatif untuk setiap kriteria</p>
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 font-medium">Alternatif</th>
                                <!-- Header kriteria akan diisi via JavaScript -->
                            </tr>
                        </thead>
                        <tbody id="bodyKeputusan">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div id="matriksNormalisasi" class="matriks-content hidden overflow-x-auto">
                    <h3 class="font-semibold mb-2">Matriks Normalisasi (R)</h3>
                    <p class="text-sm text-gray-600 mb-3">Nilai yang sudah dinormalisasi menggunakan rumus SAW</p>
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 font-medium">Alternatif</th>
                                <!-- Header kriteria akan diisi via JavaScript -->
                            </tr>
                        </thead>
                        <tbody id="bodyNormalisasi">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div id="matriksTerbobot" class="matriks-content hidden overflow-x-auto">
                    <h3 class="font-semibold mb-2">Matriks Terbobot (V)</h3>
                    <p class="text-sm text-gray-600 mb-3">Nilai normalisasi dikali bobot kriteria</p>
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 font-medium">Alternatif</th>
                                <!-- Header kriteria akan diisi via JavaScript -->
                            </tr>
                        </thead>
                        <tbody id="bodyTerbobot">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                    
                    <div class="mt-4 p-3 bg-green-50 rounded">
                        <h4 class="font-semibold mb-2">Ranking Akhir:</h4>
                        <div id="rankingAkhir" class="text-sm">
                            <!-- Akan diisi via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @php
                    // FIXED: Tampilkan semua data, bukan hanya slice 3
                    $dataItems = isset($skorAkhir['data']) ? $skorAkhir['data'] : $skorAkhir;
                    $maxSkor = isset($skorAkhir['data']) ? max(array_column($skorAkhir['data'], 'skor_total')) : max(array_column($skorAkhir, 'skor_total'));
                @endphp
                @foreach ($dataItems as $index => $item)
                    <div class="card-hover hover-zoom shadow-md rounded-lg overflow-hidden border bg-white">
                        <img src="{{ asset('assets/img/' . $item['gambar']) }}" alt="{{ $item['nama'] }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">{{ $item['nama'] }}</h3>
                            
                            <!-- Ranking Badge -->
                            @if ($index < 3)
                                <div class="mb-2">
                                    @if ($index === 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            🥇 Peringkat 1
                                        </span>
                                    @elseif ($index === 1)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            🥈 Peringkat 2
                                        </span>
                                    @elseif ($index === 2)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            🥉 Peringkat 3
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Peringkat {{ $index + 1 }}
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Skor Total dengan Visual Bar -->
                            <div class="mb-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">Skor SAW:</span>
                                    <span class="text-lg font-bold text-blue-600">{{ number_format($item['skor_total'], 4) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="progress-bar rounded-full h-2" style="width: {{ min(($item['skor_total'] / $maxSkor) * 100, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Quick Detail Skor -->
                            @if (!empty($item['detail_skor']))
                                <div class="mb-3">
                                    <p class="text-xs text-gray-600 mb-2">Kontribusi per Kriteria:</p>
                                    @foreach ($item['detail_skor'] as $kriteria => $skor)
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="text-gray-600">{{ $kriteria }}:</span>
                                            <span class="font-medium {{ $skor >= 0.2 ? 'text-green-600' : ($skor >= 0.1 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ number_format($skor, 3) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Tombol Detail -->
                            <button 
                                onclick="showDetailModal({{ $index }})" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-4 rounded transition-colors">
                                Lihat Detail Perhitungan
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Modal untuk Detail -->
            <div id="detailModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <div id="modalContent">
                        <!-- Content akan diisi via JavaScript -->
                    </div>
                </div>
            </div>

        @else
            <div class="text-center text-gray-500 mt-12">
                <p>Tidak ada rekomendasi ditemukan untuk jenis "{{ $jenis ?? 'pakaian' }}".</p>
                <div class="mt-6">
                    <a href="/" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Data untuk modal dan matriks - Handle struktur data yang baru
const skorAkhirData = @json($skorAkhir ?? []);
// FIXED: Tampilkan semua data untuk JavaScript juga
const detailData = skorAkhirData.data ? skorAkhirData.data : @json($skorAkhir ?? []);
const matriksKeputusan = skorAkhirData.matriks_keputusan || {};
const matriksNormalisasi = skorAkhirData.matriks_normalisasi || {};
const matriksTerbobot = skorAkhirData.matriks_terbobot || {};
const infoKriteria = skorAkhirData.info_kriteria || {};

// Helper function untuk membersihkan dan memformat angka
function formatCleanNumber(value, type = 'auto') {
    if (value === null || value === undefined) return '0';
    
    const num = parseFloat(value);
    
    // Untuk matriks keputusan - ini adalah nilai ASLI dari kuesioner user
    if (type === 'keputusan') {
        // Nilai dari kuesioner biasanya berupa integer (1,2,3,4,5) atau nilai sederhana
        // PENTING: Tampilkan sebagai integer jika memang integer
        if (Number.isInteger(num)) {
            return num.toString();
        }
        // Jika memang desimal dari kuesioner (rare case), tampilkan maksimal 1 desimal
        if (Math.abs(num - Math.round(num)) < 0.1) {
            return Math.round(num).toString();
        }
        return parseFloat(num.toFixed(1)).toString();
    }
    
    // Untuk normalisasi dan terbobot, gunakan 4 desimal
    if (type === 'normalisasi' || type === 'terbobot') {
        return num.toFixed(4);
    }
    
    // Auto detect - jika hampir integer, tampilkan sebagai integer
    if (Math.abs(num - Math.round(num)) < 0.0001) {
        return Math.round(num).toString();
    }
    
    // Jika desimal, batasi ke 4 digit
    return parseFloat(num.toFixed(4)).toString();
}

// Debug function untuk melihat data mentah
function debugMatriksData() {
    console.log('=== DEBUG MATRIKS DATA ===');
    console.log('matriksKeputusan:', matriksKeputusan);
    console.log('Sample data types:');
    if (Object.keys(matriksKeputusan).length > 0) {
        const firstAlternatif = Object.keys(matriksKeputusan)[0];
        const firstData = matriksKeputusan[firstAlternatif];
        Object.entries(firstData).forEach(([key, value]) => {
            console.log(`${key}: ${value} (type: ${typeof value}, isInteger: ${Number.isInteger(parseFloat(value))})`);
        });
    }
    console.log('========================');
}

// Toggle tampilan matriks
function toggleMatriks() {
    const section = document.getElementById('matriksSection');
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        initializeMatriks();
    } else {
        section.classList.add('hidden');
    }
}

// Initialize matriks data
function initializeMatriks() {
    // Debug data untuk troubleshooting
    debugMatriksData();
    
    // Populate info kriteria dengan layout horizontal
    let infoHtml = '';
    Object.values(infoKriteria).forEach(kriteria => {
        const jenisColor = kriteria.jenis.toLowerCase() === 'cost' ? 'text-red-600' : 'text-green-600';
        const jenisIcon = kriteria.jenis.toLowerCase() === 'cost' ? '📉' : '📈';
        infoHtml += `
            <div class="flex-shrink-0 bg-white p-4 rounded-lg border shadow-sm min-w-[200px]">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-lg">${jenisIcon}</span>
                    <div class="font-semibold text-gray-800 text-sm">${kriteria.nama}</div>
                </div>
                <div class="space-y-1 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bobot:</span>
                        <span class="font-medium text-blue-600">${formatCleanNumber(kriteria.bobot)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-medium ${jenisColor}">${kriteria.jenis}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Max:</span>
                        <span class="font-medium">${formatCleanNumber(kriteria.max_nilai)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Min:</span>
                        <span class="font-medium">${formatCleanNumber(kriteria.min_nilai)}</span>
                    </div>
                </div>
            </div>
        `;
    });
    document.getElementById('infoKriteria').innerHTML = infoHtml;

    // Build matriks tables
    buildMatriksTable('keputusan', matriksKeputusan);
    buildMatriksTable('normalisasi', matriksNormalisasi);
    buildMatriksTable('terbobot', matriksTerbobot);
    
    // Build ranking
    buildRanking();
}

// Build matriks table
function buildMatriksTable(type, data) {
    if (Object.keys(data).length === 0) return;

    const kriteriaNames = Object.keys(infoKriteria);
    
    // Build header
    let headerHtml = '<th class="border border-gray-300 px-3 py-2 font-medium">Alternatif</th>';
    kriteriaNames.forEach(kriteria => {
        const jenisIcon = infoKriteria[kriteria].jenis.toLowerCase() === 'cost' ? '📉' : '📈';
        const bobotFormatted = formatCleanNumber(infoKriteria[kriteria].bobot);
        headerHtml += `<th class="border border-gray-300 px-3 py-2 font-medium text-center">
            ${kriteria} ${jenisIcon}<br>
            <span class="text-xs font-normal">(${bobotFormatted})</span>
        </th>`;
    });
    
    // Find the table header (first occurrence in each table)
    const tables = document.querySelectorAll(`#matriks${type.charAt(0).toUpperCase() + type.slice(1)} table thead tr`);
    if (tables.length > 0) {
        tables[0].innerHTML = headerHtml;
    }

    // Build body
    let bodyHtml = '';
    Object.entries(data).forEach(([alternatif, nilai]) => {
        bodyHtml += `<tr class="hover:bg-gray-50">
            <td class="border border-gray-300 px-3 py-2 font-medium">${alternatif}</td>`;
        
        kriteriaNames.forEach(kriteria => {
            const cellValue = nilai[kriteria] || 0;
            const formattedValue = formatCleanNumber(cellValue, type);
            let cellClass = '';
            
            // Color coding hanya untuk normalisasi dan terbobot
            if (type === 'normalisasi' || type === 'terbobot') {
                const numValue = parseFloat(cellValue);
                if (numValue >= 0.8) cellClass = 'bg-green-100 text-green-800';
                else if (numValue >= 0.6) cellClass = 'bg-blue-100 text-blue-800';
                else if (numValue >= 0.4) cellClass = 'bg-yellow-100 text-yellow-800';
                else cellClass = 'bg-red-100 text-red-800';
            }
            
            // Untuk matriks keputusan, jangan ada color coding khusus
            if (type === 'keputusan') {
                cellClass = 'bg-gray-50'; // Background netral untuk matriks keputusan
            }
            
            bodyHtml += `<td class="border border-gray-300 px-3 py-2 text-center ${cellClass}">
                ${formattedValue}
            </td>`;
        });
        
        // Tambahkan total untuk matriks terbobot
        if (type === 'terbobot') {
            const total = Object.values(nilai).reduce((sum, val) => sum + parseFloat(val), 0);
            bodyHtml += `<td class="border border-gray-300 px-3 py-2 text-center font-bold bg-blue-200">
                ${total.toFixed(4)}
            </td>`;
        }
        
        bodyHtml += '</tr>';
    });

    // Add total column header for terbobot
    if (type === 'terbobot') {
        const headerRow = document.querySelector(`#matriks${type.charAt(0).toUpperCase() + type.slice(1)} table thead tr`);
        if (headerRow) {
            headerRow.innerHTML += '<th class="border border-gray-300 px-3 py-2 font-medium bg-blue-200">Total Skor</th>';
        }
    }
    
    document.getElementById(`body${type.charAt(0).toUpperCase() + type.slice(1)}`).innerHTML = bodyHtml;
}

// Build ranking
function buildRanking() {
    let rankingHtml = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">';
    
    detailData.forEach((item, index) => {
        const medalIcon = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : `#${index + 1}`;
        const skorFormatted = formatCleanNumber(item.skor_total, 'terbobot');
        rankingHtml += `
            <div class="flex items-center justify-between p-2 bg-white rounded border">
                <div class="flex items-center gap-2">
                    <span class="text-lg">${medalIcon}</span>
                    <span class="font-medium">${item.nama}</span>
                </div>
                <span class="font-bold text-blue-600">${skorFormatted}</span>
            </div>
        `;
    });
    
    rankingHtml += '</div>';
    document.getElementById('rankingAkhir').innerHTML = rankingHtml;
}

// Show matriks tab
function showMatriks(type) {
    // Hide all matriks content
    document.querySelectorAll('.matriks-content').forEach(el => el.classList.add('hidden'));
    
    // Remove active class from all tabs
    document.querySelectorAll('[id^="tab"]').forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600');
        tab.classList.add('text-gray-500');
    });
    
    // Show selected matriks
    document.getElementById(`matriks${type.charAt(0).toUpperCase() + type.slice(1)}`).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(`tab${type.charAt(0).toUpperCase() + type.slice(1)}`);
    activeTab.classList.add('border-blue-500', 'text-blue-600');
    activeTab.classList.remove('text-gray-500');
}

function showDetailModal(index) {
    const item = detailData[index];
    if (!item) return;

    const skorTotalFormatted = formatCleanNumber(item.skor_total, 'terbobot');
    let content = `
        <h2 class="text-xl font-bold mb-4 text-gray-800">${item.nama}</h2>
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-blue-600 mb-2">Skor Total SAW: ${skorTotalFormatted}</h3>
        </div>
    `;

    if (item.detail_kriteria) {
        content += `
            <div class="space-y-4">
                <h4 class="font-semibold text-gray-700 border-b pb-2">Detail Perhitungan SAW per Kriteria:</h4>
        `;

        Object.entries(item.detail_kriteria).forEach(([kriteria, detail]) => {
            const statusColor = detail.skor_kriteria >= 0.2 ? 'text-green-600' : 
                               detail.skor_kriteria >= 0.1 ? 'text-yellow-600' : 'text-red-600';
            
            // NILAI ASLI dari kuesioner (untuk ditampilkan)
            const nilaiAsliFormatted = formatCleanNumber(detail.nilai_alternatif, 'keputusan');
            
            // NILAI PERHITUNGAN (jika ada modifikasi similarity boost)
            const nilaiPerhitunganFormatted = detail.nilai_perhitungan ? 
                formatCleanNumber(detail.nilai_perhitungan, 'normalisasi') : nilaiAsliFormatted;
            
            const bobotFormatted = formatCleanNumber(detail.bobot);
            const maxNilaiFormatted = formatCleanNumber(detail.max_nilai);
            const minNilaiFormatted = formatCleanNumber(detail.min_nilai);
            const normalisasiFormatted = formatCleanNumber(detail.normalisasi, 'normalisasi');
            const skorKriteriaFormatted = formatCleanNumber(detail.skor_kriteria, 'terbobot');
            
            content += `
                <div class="bg-gray-50 p-3 rounded border-l-4 border-blue-400">
                    <h5 class="font-medium text-gray-800 mb-3">${kriteria}</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nilai Asli:</span>
                                <span class="font-medium">${nilaiAsliFormatted}</span>
                            </div>
                            ${detail.nilai_perhitungan ? `
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nilai Perhitungan:</span>
                                <span class="font-medium text-orange-600">${nilaiPerhitunganFormatted}</span>
                            </div>
                            ` : ''}
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bobot:</span>
                                <span class="font-medium text-blue-600">${bobotFormatted}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Range:</span>
                                <span class="font-medium">${maxNilaiFormatted} - ${minNilaiFormatted}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Normalisasi:</span>
                                <span class="font-medium text-purple-600">${normalisasiFormatted}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Skor Akhir:</span>
                                <span class="font-bold ${statusColor}">${skorKriteriaFormatted}</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                Formula: (${detail.nilai_perhitungan ? nilaiPerhitunganFormatted : nilaiAsliFormatted} ÷ ${maxNilaiFormatted}) × ${bobotFormatted}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        content += '</div>';
    }

    // Add comparison section if available
    if (item.perbandingan_dengan_ideal) {
        content += `
            <div class="mt-6 pt-4 border-t">
                <h4 class="font-semibold text-gray-700 mb-3">Perbandingan dengan Solusi Ideal:</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-green-50 p-3 rounded border">
                        <h5 class="font-medium text-green-800 mb-2">Kekuatan</h5>
                        <ul class="text-sm text-green-700 space-y-1">
                            ${item.perbandingan_dengan_ideal.kekuatan.map(k => `<li>• ${k}</li>`).join('')}
                        </ul>
                    </div>
                    <div class="bg-red-50 p-3 rounded border">
                        <h5 class="font-medium text-red-800 mb-2">Area Perbaikan</h5>
                        <ul class="text-sm text-red-700 space-y-1">
                            ${item.perbandingan_dengan_ideal.kelemahan.map(k => `<li>• ${k}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            </div>
        `;
    }

    // Show modal
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('detailModal').classList.remove('hidden');
}

// Close modal
function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Event listener untuk close modal ketika click di luar
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
    
    // Initialize matriks jika sudah ada data
    if (Object.keys(matriksKeputusan).length > 0) {
        const matriksSection = document.getElementById('matriksSection');
        if (matriksSection && !matriksSection.classList.contains('hidden')) {
            initializeMatriks();
        }
    }
});

// Function untuk export data (bonus feature)
function exportMatriksData() {
    const dataToExport = {
        info_kriteria: infoKriteria,
        matriks_keputusan: matriksKeputusan,
        matriks_normalisasi: matriksNormalisasi,
        matriks_terbobot: matriksTerbobot,
        ranking: detailData.map((item, index) => ({
            rank: index + 1,
            nama: item.nama,
            skor_total: item.skor_total
        }))
    };
    
    const dataStr = JSON.stringify(dataToExport, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `saw_analysis_${new Date().toISOString().split('T')[0]}.json`;
    link.click();
    URL.revokeObjectURL(url);
}

// Helper function untuk print matriks
function printMatriks() {
    const printWindow = window.open('', '_blank');
    const matriksContent = document.getElementById('matriksSection').innerHTML;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>SAW Analysis Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { border-collapse: collapse; width: 100%; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .bg-green-100 { background-color: #dcfce7; }
                .bg-blue-100 { background-color: #dbeafe; }
                .bg-yellow-100 { background-color: #fef3c7; }
                .bg-red-100 { background-color: #fee2e2; }
                .bg-gray-50 { background-color: #f9fafb; }
                .bg-blue-200 { background-color: #bfdbfe; }
                h2, h3 { color: #1f2937; margin-top: 30px; }
                .no-print { display: none; }
            </style>
        </head>
        <body>
            <h1>Laporan Analisis SAW (Simple Additive Weighting)</h1>
            <p>Tanggal: ${new Date().toLocaleDateString('id-ID')}</p>
            ${matriksContent}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}
    </script>
    
    <!-- Add some custom styles for better mobile experience -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .modal {
                display: none !important;
            }
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
        
        /* Smooth scrolling for better UX */
        html {
            scroll-behavior: smooth;
        }
        
        /* Better focus states for accessibility */
        button:focus,
        a:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        
        /* Loading animation */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Responsive improvements */
        @media (max-width: 640px) {
            .modal-content {
                margin: 2% auto;
                width: 95%;
                max-height: 90vh;
            }
            
            .grid {
                gap: 1rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
        }
        
        /* Animation for cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-hover {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .card-hover:nth-child(1) { animation-delay: 0.1s; }
        .card-hover:nth-child(2) { animation-delay: 0.2s; }
        .card-hover:nth-child(3) { animation-delay: 0.3s; }
        .card-hover:nth-child(4) { animation-delay: 0.4s; }
        .card-hover:nth-child(5) { animation-delay: 0.5s; }
        .card-hover:nth-child(6) { animation-delay: 0.6s; }
    </style>
</body>
</html>