<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizHistory;
use Illuminate\Http\Request;

// ... other use

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5); // Ambil dari query string, default 10
        $riwayat = QuizHistory::orderBy('created_at', 'desc')
                    ->paginate($perPage)
                    ->withQueryString(); // penting agar per_page tetap ada saat klik pagination

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
