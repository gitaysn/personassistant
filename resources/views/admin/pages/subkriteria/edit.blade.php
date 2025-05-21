@extends('admin.layouts.base')

@section('title', 'Edit Sub Kriteria')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-pencil-square"></i> Edit Subkriteria untuk Kriteria: {{ $kriteria->nama_kriteria }}
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.pages.subkriteria.update', $subkriteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="kriteria_id" value="{{ $kriteria->id }}">

            <div class="mb-3">
                <label for="name" class="form-label">Nama Subkriteria</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $subkriteria->nama_subkriteria }}" required>
            </div>

            <div class="mb-3">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="number" name="nilai" id="nilai" class="form-control" value="{{ $subkriteria->nilai }}" required min="0" step="1">
            </div>

            <button type="submit" class="btn" style="background-color: #006400; border-color: #006400; color: white;">
                Simpan
            </button>
            <a href="{{ route('admin.pages.subkriteria.kriteria', $kriteria->id) }}" class="btn" style="background-color: #90ee90; border-color: #90ee90; color: black;">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection
