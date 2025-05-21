@extends('admin.layouts.base')

@section('title', 'Data Alternatif')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-boxes"></i> Data Alternatif
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold" style="color: #064E3B;">
            <i class="bi bi-table"></i> Daftar Data Alternatif
        </h6>
        <button type="button" class="btn" style="background-color: #064E3B; color: white;" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i></i> Tambah Data
        </button>
    </div>
    
    <style>
        .label-spacing {
            margin-right: 0.3rem; /* Jarak antara label dan select */
        }
        .search-input {
            width: 200px; /* Lebar input search */
        }
        .justify-between {
            justify-content: space-between;
        }

        .pagination {
            display: flex;
            justify-content: flex-end; /* Geser ke pojok kanan */
            align-items: center;
            padding: 10px;
        }

        .pagination .page-item .page-link {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            color: white;
            background-color: #064E3B; /* Warna biru */
            margin: 0 2px;
            transition: 0.3s ease-in-out;
        }

        .pagination .page-item.active .page-link {
            background-color: #053B2D; /* Biru lebih gelap */
            font-weight: bold;
        }

        .pagination .page-item .page-link:hover {
            background-color:  #046C4E;
        }

        .pagination .page-item.disabled .page-link {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }
        
        .entries-form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .entries-left,
        .entries-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 14px;
        }

        .entries-left label,
        .entries-right label {
            margin: 0;
            line-height: 1;
        }

        .entries-left select,
        .entries-right input[type="text"] {
            padding: 4px 8px;
            height: 32px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .entries-right button {
            height: 32px;
            padding: 0 12px;
            font-size: 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .entries-right button:hover {
            background-color: #0056b3;
        }

    </style>
    
    <div class="card-body">
        <div class="d-flex align-items-center justify-between mb-3">
            <div class="d-flex align-items-center">
                <form method="GET" id="entriesForm" action="{{ route('admin.alternatif.index') }}" class="entries-form mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                    {{-- Kiri: Dropdown jumlah data --}}
                    <div class="entries-left">
                        <label for="entriesPerPage">Show</label>
                        <select name="entries" id="entriesPerPage" onchange="document.getElementById('entriesForm').submit()">
                            <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <span>entries</span>
                    </div>

                    {{-- Kanan: Search input --}}
                    <div class="entries-right" style="display: flex; align-items: center; gap: 0.5rem;">
                        <label for="search" class="me-2">Search:</label>
                        <input type="text" name="search" id="search" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit">Cari</button>
                    </div>

                    {{-- Hidden --}}
                    @if(request('jenis_pakaian'))
                        <input type="hidden" name="jenis_pakaian" value="{{ request('jenis_pakaian') }}">
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Gambar</th>
                            <th class="text-center">Nama Alternatif</th>
                            <th class="text-center">Jenis Pakaian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @php
                            $no = ($alternatif->currentPage() - 1) * $alternatif->perPage() + 1;
                        @endphp
                        @foreach ($alternatif as $key => $item)
                        <tr class="data-row">
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center">
                                @if($item->gambar)
                                    <img src="{{ asset($item->gambar) }}" alt="Gambar" style="max-width: 100px; max-height: 100px;">
                                @else
                                    Tidak ada gambar
                                @endif
                            </td>
                            <td class="text-center data-item">{{ $item->nama_alternatif }}</td>
                            <td class="text-center">{{ $item->subkriteria?->nama_subkriteria ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap: 10px;">
                                    <button type="button" class="btn btn-primary btn-sm edit-btn"
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama_alternatif }}"
                                        data-subkriteria="{{ $item->subkriteria_id }}"
                                        data-gambar="{{ $item->gambar }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.alternatif.destroy', $item->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-button" data-id="{{ $item->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $alternatif->appends(['entries' => request('entries')])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Tambah Data Alternatif -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">
                        <i class="bi bi-plus-circle"></i> Tambah Alternatif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body Modal -->
                <div class="modal-body">
                    <form action="{{ route('admin.alternatif.store') }}" method="POST" enctype="multipart/form-data"> 
                        @csrf
                        <!-- Nama Alternatif -->
                        <div class="mb-3">
                            <label for="nama_alternatif" class="form-label">Nama Alternatif</label>
                            <input type="text" class="form-control" id="nama_alternatif" name="nama_alternatif" required>
                        </div>

                        <!-- Jenis Pakaian -->
                        <div class="mb-3">
                            <label for="subkriteria_id" class="form-label fw-bold">Jenis Pakaian</label>
                            <select name="subkriteria_id" id="subkriteria_id" class="form-control" required>
                                <option value="">Pilih Jenis Pakaian</option>
                                @foreach ($subkriteria as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->nama_subkriteria }}</option>
                                @endforeach
                            </select>                        
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Upload Gambar</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                        </div>

                        <!-- Tombol Simpan & Batal -->
                        <div class="d-flex justify-content-end" style="gap: 10px;">
                            <button type="button" class="btn" style="background-color: #90ee90; color: black;" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn" style="background-color: #064E3B; color: white;">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Tambahkan modal-lg -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Data Alternatif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formEdit" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">

                        <!-- Nama Alternatif -->
                        <div class="mb-3">
                            <label for="edit_nama_alternatif" class="form-label">Nama Alternatif</label>
                            <input type="text" id="edit_nama_alternatif" name="nama_alternatif" class="form-control">
                        </div>

                        <!-- Jenis Pakaian -->
                        <div class="mb-3">
                            <label for="edit_subkriteria_id" class="form-label fw-bold">Jenis Pakaian</label>
                            <select name="subkriteria_id" id="edit_subkriteria_id" class="form-control" required>
                                <option value="">Pilih Jenis Pakaian</option>
                                @foreach ($subkriteria as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->nama_subkriteria }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-3">
                            <label for="edit_gambar" class="form-label">Upload Gambar</label>
                            <input type="file" name="gambar" id="edit_gambar" class="form-control">

                            <!-- Tampilkan gambar lama -->
                            <div id="preview_gambar_lama" class="mt-2">
                                <img src="" id="gambar_lama" alt="Gambar Lama" width="120">
                            </div>
                        </div>

                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: #90ee90; color: black;" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn" style="background-color: #064E3B; color: white;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambahkan SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).on('click', '.edit-btn', function () {
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        let subkriteria = $(this).data('subkriteria');
        let gambar = $(this).data('gambar');

        $('#edit_id').val(id);
        $('#edit_nama_alternatif').val(nama);
        $('#edit_subkriteria_id').val(subkriteria);
        $('#gambar_lama').attr('src', '/' + gambar);

        // Set action URL dinamis
        $('#formEdit').attr('action', '{{ route("admin.alternatif.update", ":id") }}'.replace(':id', id));
        $('#modalEdit').modal('show');
    });

    $('#formEdit').on('submit', function (e) {
        e.preventDefault();

        let form = $(this)[0];
        let data = new FormData(form);
        let actionUrl = $(this).attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (res) {
                // alert(res.message); // Dihapus agar tidak muncul pop-up
                $('#modalEdit').modal('hide');
                location.reload(); // Bisa diganti dengan update DOM jika tidak ingin reload
            },
            error: function (xhr) {
                alert("Gagal mengubah data.");
            }
        });
    });
</script>

    <script>
        $('#modalEdit').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);

            const id = button.data('id');
            const nama = button.data('nama');
            const subkriteria = button.data('subkriteria-id');

            // Isi form
            $('#edit_id').val(id);
            $('#edit_nama_alternatif').val(nama);
            $('#edit_subkriteria_id').val(subkriteria);

            // Ubah action-nya
            $('#formEdit').attr('action', '/alternatif/' + id);
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const editButtons = document.querySelectorAll(".edit-btn");
        const formEdit = document.getElementById("formEdit");

        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                const subkriteria = this.dataset.subkriteria;

                document.getElementById("edit_id").value = id;
                document.getElementById("edit_nama_alternatif").value = nama;
                document.getElementById("edit_subkriteria_id").value = subkriteria;

                // ⬇️ Perhatikan ini: Sesuaikan dengan route manual kamu
                formEdit.action = `/alternatif/${id}`;

                // Tampilkan modal
                const modal = new bootstrap.Modal(document.getElementById("modalEdit"));
                modal.show();
            });
        });
    });
</script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let editButtons = document.querySelectorAll(".edit-btn");

        editButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                // Ambil data dari tombol
                let id = this.getAttribute("data-id");
                let nama = this.getAttribute("data-nama");
                let subkriteriaId = this.getAttribute("data-subkriteria");

                // Isi form di modal
                document.getElementById("edit_id").value = id;
                document.getElementById("edit_nama_alternatif").value = nama;
                document.getElementById("edit_subkriteria_id").value = subkriteriaId;

                // Ubah action form ke /alternatif/{id}
                document.getElementById("formEdit").action = "/alternatif/" + id;

                // Tampilkan modal (kalau pakai Bootstrap 5)
                let modal = new bootstrap.Modal(document.getElementById('modalEdit'));
                modal.show();
            });
        });
    });
    </script>

    <script>
        function updateEntries() {
            let selectedValue = document.getElementById("entriesPerPage").value;
            let url = new URL(window.location.href);
            url.searchParams.set("entries", selectedValue);
            window.location.href = url.toString();
        }
    </script> 

<script>
    function searchFunction() {
        let input = document.getElementById('search').value.toLowerCase();
        let rows = document.querySelectorAll('.data-row');

        rows.forEach(row => {
            let text = row.querySelector('.data-item').textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ambil semua tombol dengan class "edit-btn"
        let editButtons = document.querySelectorAll(".edit-btn");

        editButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                // Ambil data dari atribut data-*
                let id = this.getAttribute("data-id");
                let nama = this.getAttribute("data-nama");
                let subkriteriaId = this.getAttribute("data-subkriteria");

                // Set nilai input di modal
                document.getElementById("edit_id").value = id;
                document.getElementById("edit_nama_alternatif").value = nama;

                // Set dropdown "Jenis Pakaian"
                let dropdown = document.getElementById("edit_subkriteria_id");
                for (let i = 0; i < dropdown.options.length; i++) {
                    if (dropdown.options[i].value == subkriteriaId) {
                        dropdown.options[i].selected = true;
                        break;
                    }
                }

                // Set action form ke URL update
                document.getElementById("formEdit").action = "/alternatif/" + id;
            });
        });
    });
</script>

<script>
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        $.get("/admin/dataalternatif/" + id + "/edit", function(data) {
            $('#edit_id').val(data.id);
            $('#edit_nama_alternatif').val(data.nama_alternatif);

            // Update form action agar konsisten
            $('#formEdit').attr('action', '/admin/dataalternatif/' + id);
            
            // Tampilkan modal
            $('#modalEdit').modal('show');
        });
    });
</script>

<script>
    $('#modalEdit').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let id = button.data('id'); // Pastikan tombol punya data-id
    let nama = button.data('nama');
    let subkriteria_id = button.data('subkriteria');
    let gambar = button.data('gambar'); // Optional

    let modal = $(this);
    modal.find('#edit_id').val(id);
    modal.find('#edit_nama_alternatif').val(nama);
    modal.find('#edit_subkriteria_id').val(subkriteria_id);

    // Set form action URL (pastikan sesuai route update)
    $('#formEdit').attr('action', '/admin/alternatif/' + id);
});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let editButtons = document.querySelectorAll(".edit-btn");

        editButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let nama = this.getAttribute("data-nama"); // Ambil nama alternatif dari tombol

                // Masukkan data ke input di modal
                document.getElementById("edit_nama_alternatif").value = nama || ""; // Jangan biarkan NULL
                document.getElementById("formEdit").action = "/alternatif/" + id;
            });
        });
    });
</script>

<script>
    document.getElementById("filterKriteria").addEventListener("change", function() {
        let selectedValue = this.value;
        let rows = document.querySelectorAll(".data-row");

        rows.forEach(row => {
            if (selectedValue === "" || row.dataset.kriteriaId === selectedValue) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Form Edit Data
        $('#formEdit').submit(function (event) {
            event.preventDefault(); // Mencegah reload halaman
            
            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize(); // Ambil data form

            $.ajax({
            url: url,
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                console.log("Respon dari server:", response); // Debugging
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil diperbarui!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || "Data tidak dapat diperbarui.",
                    });
                }
            },
            error: function (xhr) {
                console.log("Error AJAX:", xhr.responseText);

                    let errorMsg = "Terjadi kesalahan!";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMsg,
                    });
                }
            });
        });

        // SweetAlert untuk konfirmasi hapus data
        $(document).on('click', '.delete-button', function (event) {
        event.preventDefault();

        let button = $(this);
        let form = button.closest('form');
        let url = form.attr('action');
        let row = button.closest('tr'); // Ambil elemen baris yang ingin dihapus

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
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success) {
                            // Animasi fade out sebelum elemen dihapus
                            row.fadeOut(300, function () {
                                $(this).remove();
                                updateRowNumbers(); // Update nomor setelah dihapus
                            });

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
                    },
                    error: function () {
                        Swal.fire("Error!", "Terjadi kesalahan saat menghapus data.", "error");
                    }
                });
            }
        });
    });

    // Fungsi untuk memperbarui nomor urut setelah data dihapus
    function updateRowNumbers() {
        $('tbody tr').each(function (index) {
            $(this).find('td:first').text(index + 1);
        });
    }
        // Tampilkan SweetAlert jika ada session success
        let successMessage = "{{ session('success') }}";
        if (successMessage && successMessage.trim() !== "") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
</script>

@endsection
