<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // Mengambil semua data user
        return view('admin.pages.user.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);  // Ambil data user, jika tidak ada akan 404
        return view('admin.pages.user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // Ambil data user
        return view('admin.pages.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Ambil user berdasarkan ID

        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed', // validasi password + konfirmasi
        ]);

        // Ambil data yang akan diperbarui
        $data = $request->only(['name', 'email', 'username']);

        // Jika password diisi, enkripsi dan tambahkan ke data
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data user di database
        $user->update($data);

        return redirect()->route('user.show', $user->id)->with('success', 'User berhasil diperbarui');
    }

}
