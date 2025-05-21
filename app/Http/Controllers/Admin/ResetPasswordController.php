<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        return view('admin.pages.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        // Validasi input user
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Logging request untuk debugging (hindari menyimpan password di log dalam produksi)
        Log::info('Reset Password Request:', $request->only('email', 'password', 'password_confirmation', 'token'));  // Ganti \Log menjadi Log

        // Melakukan proses reset password menggunakan Laravel bawaan
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Jika berhasil, set password baru dan generate ulang remember_token
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        // Logging status hasil reset
        Log::info('Password Reset Status:', ['status' => $status]);  // Ganti \Log menjadi Log

        // Jika berhasil reset, redirect ke halaman login dengan pesan sukses
        if ($status === Password::PASSWORD_RESET) {
            return redirect('/login')->with('status', 'Password berhasil direset. Silakan login.');
        } else {
            // Jika gagal, kembalikan ke halaman sebelumnya dengan error message
            return back()->withErrors(['email' => [__($status)]]);
        }
    }
}
