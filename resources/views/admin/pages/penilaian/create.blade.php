@extends('admin.layouts.base')

@section('title', 'Tambah Data Penilaian')

@section('content')
<h1 class="h3 mb-4 text-grey-800">
    <i class="bi bi-database"></i> Data Penilaian
</h1>

<div class="card shadow mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="text-primary fw-bold">+ Tambah Data Penilaian</span>
        <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary">
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

        <form action="{{ route('admin.penilaian.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Pilih Alternatif -->
                <div class="col-md-6 mb-3">
                    <label for="alternatif_id" class="form-label">Nama Alternatif</label>
                    <select class="form-control" id="alternatif_id" name="alternatif_id" required>
                        <option value="" selected disabled>--Pilih Alternatif--</option>
                        @foreach ($alternatif as $alt)
                            <option value="{{ $alt->id }}">{{ $alt->nama_alternatif }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Jenis Pakaian -->
                <div class="col-md-6 mb-3">
                    <label for="subkriteria_id" class="form-label">Jenis Pakaian</label> <!-- ✅ Perubahan Nama -->
                    <select class="form-control" id="subkriteria_id" name="subkriteria_id" required>
                        <option value="" selected disabled>--Pilih Jenis Pakaian--</option> <!-- ✅ Perubahan Nama -->
                        @foreach ($subkriteria->where('kriteria_id', 3) as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->nama_subkriteria }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Kriteria -->
                <div class="col-md-6 mb-3">
                    <label for="kriteria_id" class="form-label">Nama Kriteria</label>
                    <select class="form-control" id="kriteria_id" name="kriteria_id" required>
                        <option value="" selected disabled>--Pilih Kriteria--</option>
                        @foreach ($kriteria->where('id', 3) as $kri)
                            <option value="{{ $kri->id }}">{{ $kri->nama_kriteria }}</option>
                        @endforeach
                    </select>
                </div>                

                <!-- Input Nilai C1 - C6 -->
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <div class="col-4 mb-2">
                            <label for="nilai_c1" class="form-label">C1</label>
                            <input type="number" step="0.01" class="form-control" id="nilai_c1" name="nilai[C1]" required>
                        </div>
                        <div class="col-4 mb-2">
                            <label for="nilai_c2" class="form-label">C2</label>
                            <input type="number" step="0.01" class="form-control" id="nilai_c2" name="nilai[C2]" required>
                        </div>
                        <div class="col-4 mb-2">
                            <label for="nilai_c3" class="form-label">C3</label>
                            <input type="number" step="0.01" class="form-control" id="nilai_c3" name="nilai[C3]" required>
                        </div>
                        <div class="col-4 mb-2">
                            <label for="nilai_c4" class="form-label">C4</label>
                            <input type="number" step="0.01" class="form-control" id="nilai_c4" name="nilai[C4]" required>
                        </div>
                        <div class="col-4 mb-2">
                            <label for="nilai_c5" class="form-label">C5</label>
                            <input type="number" step="0.01" class="form-control" id="nilai_c5" name="nilai[C5]" required>
                        </div>
                        <div class="col-4 mb-2">
                            <label for="nilai_c6" class="form-label">C6</label>
                            <input type="number" step="0.01" class="form-control" id="nilai_c6" name="nilai[C6]" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success me-3">
                <i class="bi bi-save"></i> Simpan
            </button>
            <button type="reset" class="btn btn-info">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </button>
        </form>
    </div>
</div>
@endsection
