@extends('admin.layouts.base')

@section('title', 'Data Hasil Akhir')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">
        <i class="bi bi-graph-up"></i> Data Hasil Akhir
    </h1>
    
    {{-- Tombol hanya muncul jika ada data --}}
    <a href="{{ route('admin.hasil.export', ['jenis' => $jenis]) }}" class="btn btn-success" style="background-color: #064e03; border-color: #064e03;" target="_blank">
        Download PDF
    </a>

</div>

<!-- CARD 1: Skor Akhir -->
<div class="card shadow mb-4 jenis-section" id="skor_akhir">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">
            Skor Akhir - Jenis Pakaian: {{ ucfirst($jenis) }}
        </h6>
    </div>
    <div class="card-body">
        @if (count($skorAkhir) > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Peringkat</th>
                            <th class="text-center">Nama Alternatif</th>
                            <th class="text-center">Skor Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skorAkhir as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $item['nama'] }}</td>
                            <td class="text-center">{{ number_format($item['skor'], 3) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted">
                <em>Tidak ada data skor akhir untuk jenis pakaian ini.</em>
            </div>
        @endif
    </div>
</div>
@endsection
