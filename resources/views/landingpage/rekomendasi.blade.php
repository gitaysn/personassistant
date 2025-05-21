<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekomendasi Pakaian - {{ is_array($jenis) ? implode(', ', $jenis) : ucfirst($jenis) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 2rem;
            background-color: #f9fafb;
            color: #1f2937;
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #6b7280;
            text-align: center;
            margin-top: 0;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: auto; /* Biarkan tinggi mengikuti proporsi gambar */
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .ranking {
            font-weight: 700;
            color: #10b981;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .nama-alternatif {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .skor {
            font-size: 1rem;
            color: #4b5563;
        }

        .no-gambar {
            color: #9ca3af;
            font-style: italic;
            margin-bottom: 1rem;
        }

       .back-button {
            display: inline-block;
            margin-top: 2rem;
            background-color: #065f46; /* Hijau gelap */
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .back-button:hover {
            background-color: #064e3b; /* Lebih gelap saat hover */
        }
    </style>
</head>
<body>

    <h2>Rekomendasi Pakaian: {{ is_array($jenis) ? implode(', ', $jenis) : ucfirst($jenis) }}</h2>
    <h4>Top Alternatif Berdasarkan Skor Akhir</h4>

    <div class="cards-container">
        @foreach (array_slice($skorAkhir, 0, 3) as $index => $alt)
            <div class="card">
                <div class="ranking">#{{ $index + 1 }}</div>

                @if (!empty($alt['gambar']) && file_exists(public_path($alt['gambar'])))
                    <img src="{{ asset($alt['gambar']) }}" alt="{{ $alt['nama_alternatif'] ?? 'Gambar' }}">
                @else
                    <div class="no-gambar">Tidak ada gambar</div>
                @endif

                <div class="nama-alternatif">{{ $alt['nama'] }}</div>
                <div class="skor">Skor: {{ number_format($alt['skor'], 3) }}</div>
            </div>
        @endforeach
    </div>

    <div style="text-align: center;"> 
        <a href="{{ route('home') }}#pilihpakaian" class="back-button">Kembali ke Halaman Awal</a>
    </div>

</body>
</html>
