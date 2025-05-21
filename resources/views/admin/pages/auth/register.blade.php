<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-image: url('assets/img/background.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
    }

    .cream-blur {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 253, 208, 0.8); /* cream semi-transparan */
    }
  </style>
</head>
<body class="flex justify-center items-center text-gray-800 font-sans">

  <div class="cream-blur rounded-xl p-8 w-[420px] border border-white/30 shadow-lg">
    <h2 class="text-center text-2xl font-bold mb-2">Daftar Akun</h2>
    <p class="text-center text-sm text-gray-700 mb-4">Silakan isi form di bawah untuk membuat akun.</p>

    <form action="{{ route('register.submit') }}" method="POST">
      @csrf
      <div class="space-y-3">
        <div>
          <label class="block text-sm">Nama Lengkap</label>
          <input type="text" name="name" class="w-full px-4 py-2 rounded bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="block text-sm">Email</label>
          <input type="email" name="email" class="w-full px-4 py-2 rounded bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="block text-sm">Username</label>
          <input type="text" name="username" class="w-full px-4 py-2 rounded bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="block text-sm">Password</label>
          <input type="password" name="password" class="w-full px-4 py-2 rounded bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="block text-sm">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="w-full px-4 py-2 rounded bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
      </div>

      <div class="flex justify-between mt-6 gap-3">
        <a href="{{ route('login') }}" class="w-1/2 bg-gray-500 text-white px-4 py-2 rounded text-center text-sm hover:bg-gray-600 transition">Kembali</a>
        <button type="submit" class="w-1/2 bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">Daftar</button>
      </div>
    </form>
  </div>

</body>
</html>
