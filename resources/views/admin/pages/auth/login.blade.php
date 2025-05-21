<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center items-center min-h-screen bg-white text-gray-800 font-sans">
  <div class="bg-green-100 rounded-xl px-6 py-8 w-[32rem] border border-green-200 shadow-lg">
    <h2 class="text-2xl font-bold mb-2">
        <span>Welcome Back</span> <span>Administrator</span>
    </h2>
    <p class="text-sm text-gray-600 mb-6">Please enter your details</p>

    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div class="mb-4">
        <label class="block text-sm mb-1">Username</label>
        <input type="text" name="username" class="w-full px-4 py-2 rounded bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <div class="mb-4">
        <label class="block text-sm mb-1">Password</label>
        <input type="password" name="password" class="w-full px-4 py-2 rounded bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <div class="flex items-center justify-between text-sm mb-4">
        <label class="flex items-center">
          <input type="checkbox" class="mr-2"> Remember me
        </label>
        <a href="{{ route('forgot.password.form') }}" class="text-green-700 hover:underline font-medium">
          Lupa Password?
        </a>
      </div>      
      <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
        Login
      </button>
    </form>    
  </div>

  {{-- SweetAlert untuk status sukses --}}
  @if (session('status'))
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  {{-- SweetAlert untuk login gagal --}}
  @if ($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: '{{ $errors->first() }}',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Coba Lagi'
      });
    </script>
  @endif
  
</body>
</html>
