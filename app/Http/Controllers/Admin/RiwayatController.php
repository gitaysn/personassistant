<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizHistory;

// ... other use

class RiwayatController extends Controller
{
    public function index()
    {
         $riwayat = QuizHistory::latest()->get(); // ambil semua data, urut terbaru dulu

        // Misalnya return view
        return view('admin.pages.riwayat.index', compact('riwayat'));
    }

    public function destroy($id)
    {
        try {
            $riwayat = QuizHistory::findOrFail($id);
            $riwayat->delete();

            return redirect()->back()->with('success', 'Riwayat berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus riwayat.');
        }
    }

}
