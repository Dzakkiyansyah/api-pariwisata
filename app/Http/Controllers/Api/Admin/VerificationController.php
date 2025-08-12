<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\MitraApplication;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    // Menampilkan semua pendaftar yang statusnya 'pending'
    public function index()
    {
        $applications = MitraApplication::with('user')->where('status', 'pending')->get();
        return response()->json($applications);
    }

    // Menyetujui pendaftaran
    public function approve($id)
    {
        // Menggunakan nama variabel tunggal '$application'
        $application = MitraApplication::findOrFail($id);
        $application->update(['status' => 'approved']);

        // notifikasi disetujui
        return response()->json([
            'message' => 'Pendaftaran untuk ' . $application->business_name . ' berhasil disetujui.',
        ]);
    }

    // Menolak pendaftaran
    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        // Menggunakan nama variabel tunggal
        $application = MitraApplication::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);

        // notifikasi ditolak
        return response()->json([
            'message' => 'Pendaftaran untuk ' . $application->business_name . ' ditolak.',
        ]);
    }
}
