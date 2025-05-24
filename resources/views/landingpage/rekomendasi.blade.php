<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi {{ $jenis }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Rekomendasi {{ $jenis }}</h1>
        
        @if(isset($skorAkhir) && count($skorAkhir) > 0)
            <div class="row">
                @foreach($skorAkhir as $index => $item)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    #{{ $index + 1 }} - {{ $item['alternatif']->nama_alternatif ?? 'Produk ' . ($index + 1) }}
                                </h5>
                                
                                <p class="card-text">
                                    <strong>Skor: </strong>{{ number_format($item['skor_akhir'] * 100, 1) }}%
                                </p>
                                
                                @if(isset($item['alternatif']->harga))
                                    <p class="card-text">
                                        <strong>Harga: </strong>Rp {{ number_format($item['alternatif']->harga, 0, ',', '.') }}
                                    </p>
                                @endif
                                
                                @if(isset($item['alternatif']->deskripsi))
                                    <p class="card-text">{{ $item['alternatif']->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                <p>Tidak ada rekomendasi {{ strtolower($jenis) }} yang tersedia.</p>
            </div>
        @endif
        
        <div class="mt-4">
            <a href="/" class="btn btn-secondary">Kembali ke Beranda</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>