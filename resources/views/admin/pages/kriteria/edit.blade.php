@extends('admin.layouts.base')

@section('title', 'Edit Data Kriteria')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-pencil-square"></i> Edit Data Kriteria
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

        <form action="{{ route('admin.kriteria.update', $kriteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="kode_kriteria" class="form-label">Kode Kriteria</label>
                <input type="text" class="form-control" id="kode_kriteria" name="kode_kriteria" value="{{ $kriteria->kode_kriteria }}" required>
            </div>
            <div class="mb-3">
                <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" value="{{ $kriteria->nama_kriteria }}" required>
            </div>
            <div class="mb-3">
                <label for="bobot" class="form-label">Bobot</label>
                <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" value="{{ $kriteria->bobot }}" required>
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <select class="form-control" id="jenis" name="jenis" required>
                    <option value="Benefit" {{ $kriteria->jenis == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                    <option value="Cost" {{ $kriteria->jenis == 'Cost' ? 'selected' : '' }}>Cost</option>
                </select>
            </div>
            <button type="submit" class="btn" style="background-color: #006400; border-color: #006400; color: white;">
                Simpan
            </button>
            <a href="{{ route('admin.kriteria.index') }}" class="btn" style="background-color: #90ee90; border-color: #90ee90; color: black;">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection
