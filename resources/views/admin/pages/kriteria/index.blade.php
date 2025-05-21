@extends('admin.layouts.base')

@section('title', 'Data Kriteria')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-box"></i> Data Kriteria
    </h1>
    <a href="{{ route('admin.kriteria.create') }}" class="btn btn-success" style="background-color: #006400; border-color: #004d00;">
        <i></i> Tambah Data
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color: #006400;">
            <i class="bi bi-table"></i> Daftar Data Kriteria
        </h6>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center" style="width: 120px;">Kode Kriteria</th>
                        <th class="text-center">Nama Kriteria</th>
                        <th class="text-center">Bobot</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kriteria as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $item->kode_kriteria }}</td>
                        <td class="text-center">{{ $item->nama_kriteria }}</td>
                        <td class="text-center">{{ $item->bobot }}</td>
                        <td class="text-center">{{ $item->jenis }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('admin.kriteria.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-sm mx-1">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                <button type="button" class="btn btn-danger btn-sm mx-1 delete-button" data-id="{{ $item->id }}">
                                    <i class="bi bi-trash"></i> 
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.kriteria.destroy', $item->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });
    });
</script>
@endsection