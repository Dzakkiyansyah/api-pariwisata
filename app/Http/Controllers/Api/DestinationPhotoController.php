<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DestinationPhotoController extends Controller
{
    /**
     * Menyimpan foto baru untuk sebuah destinasi.
     */
    public function store(Request $request, Destination $destination)
    {
        // Otorisasi: Pastikan user adalah pemilik destinasi atau admin
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $destination->user_id) {
            return response()->json(['message' => 'Anda tidak memiliki izin.'], 403);
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $path = $request->file('photo')->store('destination_galleries', 'public');

        $photo = $destination->photos()->create([
            'photo_path' => $path,
        ]);

        return response()->json($photo, 201);
    }

    /**
     * Menghapus sebuah foto.
     */
    public function destroy(DestinationPhoto $photo)
    {
        // Kita ambil data destinasi dari foto untuk otorisasi
        $destination = $photo->destination;

        // Otorisasi: Pastikan user adalah pemilik destinasi atau admin
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $destination->user_id) {
            return response()->json(['message' => 'Anda tidak memiliki izin.'], 403);
        }

        // Hapus file fisik dari storage
        Storage::disk('public')->delete($photo->photo_path);

        // Hapus data dari database
        $photo->delete();

        return response()->json(['message' => 'Foto berhasil dihapus.']);
    }
}
