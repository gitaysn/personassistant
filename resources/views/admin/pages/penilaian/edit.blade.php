@extends('admin.layouts.base')

@section('title', 'Edit Penilaian Alternatif')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-pencil-square"></i> Edit Penilaian Alternatif
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form untuk update penilaian -->
        <form action="{{ route('admin.penilaian.update', $alternatif->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Alternatif -->
            <div class="mb-3">
                <label class="form-label">Nama Alternatif</label>
                <input type="text" class="form-control" value="{{ $alternatif->nama_alternatif }}" readonly>
            </div>

            <div class="row">
                @foreach ($kriteria as $k)
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ $k->nama_kriteria }}</label>
                        <select class="form-control" name="subkriteria_{{ $k->id }}" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($k->subkriteria as $subkriteria)
                                <option value="{{ $subkriteria->id }}"
                                    {{ isset($nilai[$k->id]) && $nilai[$k->id] == $subkriteria->id ? 'selected' : '' }}>
                                    {{ $subkriteria->nama_subkriteria }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn" style="background-color: #064E3B; color: white;">
                Simpan
            </button>
            <a href="{{ route('admin.penilaian.index') }}" class="btn" style="background-color: #90ee90; color: black;">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection
