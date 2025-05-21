@extends('admin.layouts.base')

@section('title', 'Data Sub Kriteria')

@section('content')
<h1 class="h3 mb-4 text-grey-800">
    <i class="bi bi-list-check"></i> Subkriteria untuk Kriteria: {{ $kriteria->nama_kriteria }}
</h1>

<div class="card shadow mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span style="color: #006400;" class="fw-bold">
            <i class="bi bi-plus-circle"></i> Tambah Subkriteria
        </span>
        <a href="{{ route('admin.kriteria.index') }}" class="btn" style="background-color: #90ee90; border-color: #90ee90; color: black;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.subkriteria.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kriteria_id" value="{{ $kriteria->id }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama Subkriteria</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nilai" class="form-label">Nilai</label>
                    <input type="number" name="nilai" id="nilai" class="form-control" required min="0" step="1">
                    @error('nilai')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn me-3" style="background-color: #006400; border-color: #006400; color: white;">
                Simpan
            </button>
            <button type="reset" class="btn" style="background-color: #90ee90; border-color: #90ee90; color: black; margin-left: 10px;">
                Reset
            </button>
        </form>
    </div>
</div>
@endsection
