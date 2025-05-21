<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('admin.pages.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        // Validasi bahwa field email harus diisi dan dalam format email
        $request->validate(['email' => 'required|email']);

         // Kirim link reset password ke email yang dimasukkan
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Jika berhasil mengirim link, tampilkan pesan status
        // Jika gagal, kembalikan dengan pesan error pada field email
        return $status === Password::RESET_LINK_SENT
                    ? back()->with('status', __($status)) //sukses
                    : back()->withErrors(['email' => __($status)]); //gagal
    }
}
