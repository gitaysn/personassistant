@extends('admin.layouts.base')
@section('title','Dashboard')
@section('content')

<style>
        #content-wrapper {
        min-height: 100vh; /* Supaya kontennya minimal setinggi layar */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    #content {
        flex: 1; /* Supaya konten utama mengambil semua ruang kosong */
    }

    .sticky-footer {
    width: calc(100% - 225px); /* Sesuaikan 250px dengan lebar sidebar */
    position: fixed;
    bottom: 0;
    left: 225px; /* Mulai dari sebelah kanan sidebar */
    background-color: white;
    padding: 10px 0;
    text-align: center;
}

    .card-link-text {
        transition: text-decoration 0.2s ease;
    }

    a:hover .card-link-text {
        text-decoration: underline;
    }

    a.card-link {
        text-decoration: none;
        color: inherit; /* supaya warna teks tetap sama */
        display: block; /* supaya link memenuhi seluruh card */
    }

    a.card-link:hover span {
        text-decoration: underline;
        cursor: pointer;
    }

    a.text-decoration-none:hover .underline-on-hover {
        text-decoration: underline;
    }

</style>

<h1 class="h3 text-gray-800">
    <i class="bi bi-house-door-fill"></i> Dashboard
</h1>

<!-- Notification Pop-up -->
<div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 14px; display: flex; align-items: center; justify-content: space-between; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px;">
    <span><strong>Selamat datang ADMIN!</strong> Anda bisa mengoperasikan sistem dengan wewenang tertentu melalui pilihan menu di bawah.</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-left: 10px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

 <!-- Content Row -->
 <div class="row">

    <div class="row">

        <!-- Data Kriteria -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('admin.kriteria.index') }}" class="card-link">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 font-weight-bold text-gray-800">
                                Data Kriteria: {{ $totalKriteria }}
                            </span>
                            <i class="bi bi-box text-gray-400" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    
        <!-- Data Sub Kriteria -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ $kriteriaId ? route('admin.pages.subkriteria.kriteria', ['id' => $kriteriaId]) : '#' }}" class="card-link">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 font-weight-bold text-gray-800">
                                Data Sub Kriteria: {{ $totalSubKriteria }}
                            </span>
                            <i class="bi bi-boxes text-gray-400" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    
        <!-- Data Alternatif -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('admin.alternatif.index') }}" class="text-decoration-none">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 font-weight-bold text-gray-800 underline-on-hover">
                                Data Alternatif: {{ $totalAlternatif }}
                            </span>
                            <i class="bi bi-person-lines-fill text-gray-400" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    
        <!-- Data Penilaian -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('admin.penilaian.index') }}" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 font-weight-bold text-gray-800 card-link-text">Penilaian Alternatif</span>
                            <i class="bi bi-list-ul text-gray-400" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    
        <!-- Data Perhitungan -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0 font-weight-bold text-gray-800">Perhitungan</span>
                        <i class="bi bi-calculator-fill text-gray-400" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Data Hasil Akhir -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0 font-weight-bold text-gray-800">Hasil Akhir</span>
                        <i class="bi bi-graph-up text-gray-400" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection