@extends('admin.layouts.base')

@section('title', 'Riwayat Perhitungan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-grey-800">
        <i class="bi bi-hourglass-split"></i> Riwayat Hasil Perhitungan
    </h1> 
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold" style="color: #064E3B;">
            <i class="bi bi-table"></i> Daftar Riwayat Hasil Perhitungan
        </h6>
    </div>

    <div class="card-body">
        <form method="GET" class="mb-3 d-flex align-items-center">
    <label for="per_page" class="me-2 mb-0">Tampilkan</label>
    <select name="per_page" id="per_page" class="form-select form-select-sm w-auto me-2" onchange="this.form.submit()">
        @foreach([5, 10, 20, 30] as $value)
            <option value="{{ $value }}" {{ request('per_page', 5) == $value ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>
    <span class="mb-0">entri</span>
</form>

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayat as $index => $item)
                        <tr>
                            <td class="text-center">{{ $riwayat->firstItem() + $index }}</td>
                            <td class="text-center">{{ $item->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalRiwayat{{ $item->id }}">
                                    <i class="bi bi-folder2-open"></i>
                                </button>

                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.riwayat.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="modalRiwayat{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel{{ $item->id }}">Detail Riwayat</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Jawaban Kuisioner:</h6>
                                        @php
                                            $kuisioner = is_string($item->data_kuisioner) ? json_decode($item->data_kuisioner, true) : $item->data_kuisioner;
                                        @endphp
                                        <ul>
                                            @if (is_array($kuisioner))
                                                @foreach ($kuisioner as $key => $value)
                                                    <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                                @endforeach
                                            @else
                                                <li>Data kuisioner tidak tersedia.</li>
                                            @endif
                                        </ul>

                                        <h6 class="mt-3">Hasil Rekomendasi:</h6>
                                        @php
                                            $rekomendasi = is_string($item->hasil_rekomendasi) ? json_decode($item->hasil_rekomendasi, true) : $item->hasil_rekomendasi;
                                        @endphp
                                        <ol>
                                            @if (is_array($rekomendasi))
                                                @foreach ($rekomendasi as $produk)
                                                    <li>
                                                        {{ is_array($produk) ? ($produk['nama'] ?? implode(', ', $produk)) : $produk }}
                                                    </li>
                                                @endforeach
                                            @else
                                                <li>Tidak ada rekomendasi.</li>
                                            @endif
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $riwayat->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data riwayat akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush



@endsection
