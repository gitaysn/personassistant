@extends('admin.layouts.base')

@section('title', 'Penilaian Alternatif')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-box"></i> Penilaian Alternatif
    </h1> 
</div>

<style>
    .pagination {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 10px;
    }

    .pagination .page-item .page-link {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 5px;
        border: none;
        color: white;
        background-color: #064E3B;
        margin: 0 2px;
        transition: 0.3s ease-in-out;
    }

    .pagination .page-item.active .page-link {
        background-color: #053B2D;
        font-weight: bold;
    }

    .pagination .page-item .page-link:hover {
        background-color: #046C4E;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }

    .form-select-sm,
    .form-control-sm {
        width: auto;
    }

    .search-input {
        width: 200px;
    }

    .btn-biru {
        background-color: #007bff; /* biru Bootstrap */
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-biru:hover {
        background-color: #0069d9;
    }

    .search-box {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 12px;
    }

    .search-box input[type="text"] {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

</style>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold" style="color: #064E3B;">
            <i class="bi bi-table"></i> Daftar Penilaian Alternatif
        </h6>
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="entriesPerPage" class="mb-0">Show</label>
                <select name="perPage" id="entriesPerPage" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="30" {{ request('perPage') == 30 ? 'selected' : '' }}>30</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span class="mb-0">entries</span>
            </form>            

            <form action="{{ route('admin.penilaian.index') }}" method="GET" class="search-box">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alternatif...">
                <button type="submit" class="btn-biru">Cari</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Alternatif</th>
                        <th class="text-center">C1</th>
                        <th class="text-center">C2</th>
                        <th class="text-center">C3</th>
                        <th class="text-center">C4</th>
                        <th class="text-center">C5</th>
                        <th class="text-center">C6</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $item)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $key }}</td>
                        <td class="text-center">{{ $item->nama_alternatif ?? '-' }}</td>

                        @for ($i = 1; $i <= 6; $i++)
                            @php
                                $penilaian = $item->penilaian->firstWhere('kriteria_id', $i);
                                $nilai = $penilaian && $penilaian->subkriteria ? $penilaian->subkriteria->nilai : '-';
                            @endphp
                            <td class="text-center">{{ $nilai }}</td>
                        @endfor

                        <td class="text-center">
                            <a href="{{ route('admin.penilaian.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> 
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada data penilaian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->appends(['perPage' => request('perPage')])->links('pagination::bootstrap-4') }}
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
function searchFunction() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById('search');
    filter = input.value.toUpperCase();
    table = document.querySelector("table");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header
        td = tr[i].getElementsByTagName("td")[1]; // Column 2 = Nama Alternatif
        if (td) {
            txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
}
</script>
@endsection
