@extends('admin.layouts.base')

@section('title', 'Data Sub Kriteria')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-boxes"></i> Data Sub Kriteria
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-table"></i> Jenis Acara (C1)
        </h6>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Data
        </button> 
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Sub Kriteria</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Kriteria</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subkriteria as $key => $item)
                    <tr id="subkriteria_{{ $item->id }}">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center nama">{{ $item->nama_subkriteria }}</td>
                        <td class="text-center nilai">{{ $item->nilai }}</td>
                        <td class="text-center">{{ $item->datakriteria->nama_kriteria ?? $item->kriteria_id }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center" style="gap: 10px;">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" 
                                    data-id="{{ $item->id }}" 
                                    data-nama="{{ $item->nama_subkriteria }}" 
                                    data-nilai="{{ $item->nilai }}"
                                    data-kriteria="{{ $item->kriteria_id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                <form action="{{ route('admin.subkriteria.destroy', $item->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-button">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">
                    <i class="bi bi-plus-circle"></i> Tambah Sub Kriteria
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.subkriteria.store') }}" method="POST">
                    @csrf
                    <!-- Nama Sub Kriteria -->
                    <div class="mb-3">
                        <label for="nama_subkriteria" class="form-label">Nama Sub Kriteria</label>
                        <input type="text" class="form-control" id="nama_subkriteria" name="nama_subkriteria" required>
                    </div>

                    <!-- Nilai -->
                    <div class="mb-3">
                        <label for="nilai" class="form-label">Nilai</label>
                        <input type="number" class="form-control" id="nilai" name="nilai" min="1" max="5" required>
                    </div>

                   <!-- Kriteria -->
                    <div class="mb-3">
                        <label for="kriteria_id" class="form-label fw-bold">Kriteria</label>
                        <select class="form-select" id="kriteria_id" name="kriteria_id" required>
                            <option value="" disabled selected>--- Pilih Kriteria ---</option>
                            @foreach($datakriteria as $kriteria)
                                <option value="{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Simpan & Batal -->
                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Sub Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_nama_subkriteria" class="form-label">Nama Sub Kriteria</label>
                        <input type="text" class="form-control" id="edit_nama_subkriteria" name="nama_subkriteria" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nilai" class="form-label">Nilai</label>
                        <input type="number" class="form-control" id="edit_nilai" name="nilai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        $.get("/admin/subkriteria/" + id + "/edit", function(data) {
            $('#edit_id').val(data.id);
            $('#edit_nama_subkriteria').val(data.nama_subkriteria);
            $('#edit_nilai').val(data.nilai);

            // Update form action
            $('#formEdit').attr('action', '/admin/subkriteria/' + id);
            
            // Tampilkan modal
            $('#modalEdit').modal('show');
        });
    });
</script>

<script>
document.querySelectorAll('button[data-bs-target="#modalEdit"]').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        const nama = this.getAttribute('data-nama');
        const nilai = this.getAttribute('data-nilai');
        const kriteria = this.getAttribute('data-kriteria');

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama_subkriteria').value = nama;
        document.getElementById('edit_nilai').value = nilai;
        document.getElementById('formEdit').action = `/admin/subkriteria/${id}`;
    });
});
</script>

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

    // Fungsi untuk memperbarui nomor urut setelah penghapusan
    function updateRowNumbers() {
        let rows = document.querySelectorAll("#dataTable tbody tr");
        rows.forEach((row, index) => {
            let numberCell = row.querySelector("td:first-child");
            if (numberCell) {
                numberCell.textContent = index + 1; // Perbarui nomor urut
            }
        });
    }

    // SweetAlert untuk konfirmasi hapus data
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = this.closest('form');
                    let url = form.action;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`subkriteria_${data.id}`)?.remove();
                            updateRowNumbers();

                            Swal.fire({
                                icon: 'success',
                                title: 'Data Terhapus!',
                                text: 'Data berhasil dihapus.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire("Gagal!", data.message || "Data tidak dapat dihapus.", "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Terjadi kesalahan saat menghapus data.", "error");
                    });
                }
            });
        });
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
