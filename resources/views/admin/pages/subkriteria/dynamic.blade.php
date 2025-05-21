@extends('admin.layouts.base')

@section('title', 'Data Sub Kriteria')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-boxes"></i> Data Sub Kriteria untuk: {{ $kriteria->nama_kriteria }}
    </h1>
    <a href="{{ route('admin.subkriteria.create', ['id' => $kriteria->id]) }}" class="btn" style="background-color: #006400; border-color: #006400; color: white;">
        <i></i> Tambah Data
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold" style="color: #006400;">
            <i class="bi bi-table"></i> Daftar Sub Kriteria
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Sub Kriteria</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subkriterias as $key => $sub)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $sub->nama_subkriteria }}</td>
                            <td class="text-center">{{ $sub->nilai }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.subkriteria.edit', $sub->id) }}" class="btn btn-warning btn-sm mr-3">
                                        <i class="bi bi-pencil"></i> 
                                    </a>
                                    <!-- Form untuk hapus subkriteria -->
                                    <form action="{{ route('subkriteria.destroy', $sub->id) }}" method="POST" class="form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi untuk menampilkan SweetAlert sukses
    function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        showConfirmButton: false,
        timer: 1500
    });
}

    // SweetAlert untuk konfirmasi hapus data
    document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.form-hapus');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Cegah submit langsung

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Lanjutkan submit jika dikonfirmasi
                }
            });
        });
    });
});

// Menampilkan SweetAlert saat ada pesan sukses dari server
document.addEventListener("DOMContentLoaded", function() {
    const successMessage = "{{ session('success') }}";
    if (successMessage.trim() !== "") {
        showSuccessMessage(successMessage);
    }
});

    // SweetAlert setelah menambah data
    document.addEventListener("DOMContentLoaded", function() {
        const successMessage = "{{ session('success') }}";
        if (successMessage.trim() !== "") {
            showSuccessMessage(successMessage);
        }
    });

    // SweetAlert setelah edit data
    document.getElementById('formEdit')?.addEventListener('submit', function(event) {
        event.preventDefault();  
        let form = this;
        let url = form.action;
        let formData = new FormData(form);

        fetch(url, {
            method: 'POST', // atau 'PUT' jika pakai PUT
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil diperbarui!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire("Gagal!", data.message || "Data tidak dapat diperbarui.", "error");
            }
        })
        .catch(error => {
            console.log("Error di fetch:", error);
            Swal.fire("Error!", "Terjadi kesalahan saat mengupdate data.", "error");
        });
    });
</script>

@endsection
