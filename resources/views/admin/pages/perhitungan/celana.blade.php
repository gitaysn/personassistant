@extends('admin.layouts.base')

@section('title', 'Data Perhitungan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-boxes"></i> Data Perhitungan 
    </h1>
</div>

<!-- CARD 1: Matriks Keputusan -->
<div class="card shadow mb-4 jenis-section" id="matriks_keputusan">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Matriks Keputusan (X) - {{ ucfirst($jenis) }}
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Alternatif</th>
                        @foreach ($kriteria as $index => $k)
                            <th class="text-center">C{{ $index + 1 }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alternatif as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $item->nama_alternatif ?? '-' }}</td>

                        @foreach ($kriteria as $k)
                            @php
                                $penilaian = $item->penilaian->firstWhere('kriteria_id', $k->id);
                                $nilai = $penilaian && $penilaian->subkriteria ? $penilaian->subkriteria->nilai : '-' ;
                            @endphp
                            <td class="text-center">{{ $nilai }}</td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 2 + $kriteria->count() }}" class="text-center">Belum ada data penilaian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CARD 2: Normalisasi -->
<div class="card shadow mb-4 jenis-section" id="normalisasi">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">
            Normalisasi (R) - Jenis Pakaian: {{ ucfirst($jenis) }}
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Alternatif</th>
                        @foreach ($kriteria as $index => $k)
                            <th class="text-center">C{{ $index + 1 }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($normalisasi as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item['nama'] }}</td>
                        @foreach ($kriteria as $i => $k)
                            <td class="text-center">{{ number_format($item['nilai']['C2'], 3) }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
