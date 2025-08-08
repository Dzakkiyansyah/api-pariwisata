<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\MitraApplication;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    // Menampilkan semua pendaftar yang belum diverifikasi
    public function index()
    {
        $applications = MitraApplication::with('user')-> where('status', 'pending') ->get();
        return response()->json($applications);
    }

    // Menyetujui pendaftaran
    public function approve($id)
    {
        $applications = MitraApplication:: findOrFail($id);
        $applications->update(['status' => "approved"]);

        // notifikasi disetujui
        return response()->json ([
            'message' => 'Pendaftaran berhasil disetujui',
        ]);
    }

    // Menolak pendaftaran
    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required/string'
        ]);

        $applications = MitraApplication::findOrFail($id);
        $applications->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);

        // notifikasi ditolak
        return response()->json([
            'message' => 'Pendaftaran ditolak',
        ]);
    }
}
