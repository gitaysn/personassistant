@extends('admin.layouts.base')

@section('title', 'Data User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-people-fill"></i> Data User
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color: #064e03;">
            <i class="bi bi-person-fill-gear"></i> Detail Data User
        </h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th width="30%">E-Mail</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $user->username }}</td>
            </tr>
            <tr>
                <th>Password</th>
                <td>********</td> {{-- Password sebaiknya tidak ditampilkan secara langsung --}}
            </tr>
        </table>

        <div class="mt-4 text-end">
            <a href="{{ route('user.edit', $user->id) }}" class="btn" style="background-color: #064e03; color: white;">
                <i class="bi bi-pencil-square"></i> Edit Profil
            </a>
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