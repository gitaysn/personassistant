@extends('admin.layouts.base')

@section('title', 'Tambah Data Kriteria')

@section('content')
<h1 class="h3 mb-4 text-grey-800">
    <i class="bi bi-database"></i> Data Kriteria
</h1>

<div class="card shadow mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span style="color: #006400;" class="fw-bold">
            <i class="bi bi-plus-circle"></i>  Tambah Data Kriteria
        </span>
        <a href="{{ route('admin.kriteria.index') }}" class="btn" style="background-color: #90ee90; border-color: #90ee90; color: black;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
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

        <form action="{{ route('admin.kriteria.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_kriteria" class="form-label">Kode Kriteria</label>
                    <input type="text" class="form-control" id="kode_kriteria" name="kode_kriteria" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                    <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="bobot" class="form-label">Bobot</label>
                <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" required>
            </div>
                <div class="col-md-6 mb-3">
                    <label for="jenis" class="form-label">Jenis</label>
                <select class="form-control" id="jenis" name="jenis" required>
                    <option value="" selected disabled>--Pilih Jenis--</option>
                    <option value="Benefit">Benefit</option>
                    <option value="Cost">Cost</option>
                </select>
            </div>
            </div>
                <button type="submit" class="btn me-3" style="background-color: #006400; border-color: #006400; color: white;">
                    <i></i> Simpan
                </button>
                <button type="reset" class="btn" style="background-color: #90ee90; border-color: #90ee90; color: black; margin-left: 10px;">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
        </form>
    </div>
</div>
@endsection
