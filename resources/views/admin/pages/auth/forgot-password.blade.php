<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center items-center min-h-screen bg-white text-gray-800 font-sans">
  <div class="bg-green-100 border border-green-200 p-6 rounded-xl shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4">Lupa Password?</h2>
      <p class="text-gray-600 mb-6">
        Masukkan email untuk reset password Anda.
    </p>
    
    @if (session('status'))
      <div class="bg-red-100 text-red-700 border border-red-300 p-3 rounded mb-4">
        Kami sudah mengirimkan email yang berisi tautan untuk mereset kata sandi Anda!
      </div>
    @endif

    <form method="POST" action="{{ route('forgot.password.send') }}">
      @csrf
      <div class="mb-4">
        <label for="email" class="block text-sm mb-1">Email</label>
        <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        @error('email')
          <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
      </div>
      <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded font-semibold">
        Kirim Link Reset
      </button>
    </form>
    <p class="text-center text-sm text-gray-700 mt-4">
      <a href="{{ route('login') }}" class="text-black hover:underline font-medium">
        Tidak jadi reset password? Klik di sini untuk kembali.
      </a>
    </p>    
  </div>
</body>
</html>
