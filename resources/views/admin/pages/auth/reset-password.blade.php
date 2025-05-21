@extends('admin.layouts.auth')

@section('content')
<body class="flex justify-center items-center min-h-screen text-gray-800 font-sans" style="background-color: white;">
    <div style="background-color: #ddfceb; border: 1px solid #b7e4c7;" class="p-6 rounded-xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-4 text-center">Reset Password</h2>
        <p class="text-gray-600 text-center mb-6">
            Silakan masukkan password baru Anda di bawah ini.
        </p>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SweetAlert untuk status sukses --}}
    @if (session('status'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('status') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    {{-- SweetAlert untuk error validasi --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required class="w-full border p-2 rounded @error('email') border-red-600 @enderror">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Password Baru</label>
            <input type="password" name="password" required class="w-full border p-2 rounded @error('password') border-red-600 @enderror">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required class="w-full border p-2 rounded @error('password_confirmation') border-red-600 @enderror">
        </div>

        <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white py-2 rounded">
            Reset Password
        </button>
    </form>
</div>
</body>
@endsection
