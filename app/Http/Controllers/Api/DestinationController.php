<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    /**
     * Menampilkan daftar semua destinasi yang sudah 'published'.
     * Ini adalah endpoint publik.
     */
    public function index()
    {
        $destinations = Destination::where('status', 'published')->latest()->get();
        return response()->json($destinations);
    }

    /**
     * Menyimpan destinasi baru oleh pengguna yang terotentikasi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ticket_price' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Membuat destinasi dan langsung menghubungkannya dengan user yang sedang login
        $destination = Auth::user()->destinations()->create($validated);

        return response()->json($destination, 201);
    }

    /**
     * Menampilkan satu destinasi spesifik.
     * Ini adalah endpoint publik, namun dengan logika tambahan.
     */
    public function show(Destination $destination)
    {
        // Logika: Jika statusnya bukan 'published', hanya pemilik atau admin yang boleh lihat.
        // Jika tidak, tampilkan error 'Tidak Ditemukan'.
        if ($destination->status !== 'published' && !(Auth::check() && (Auth::user()->hasRole('admin') || Auth::id() === $destination->user_id))) {
            return response()->json(['message' => 'Destinasi tidak ditemukan.'], 404);
        }

        return response()->json($destination);
    }

    /**
     * Mengupdate destinasi yang sudah ada.
     */
    public function update(Request $request, Destination $destination)
    {
        // Otorisasi: Pastikan user yang mengedit adalah pemilik destinasi ATAU seorang admin.
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $destination->user_id) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'ticket_price' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'status' => 'sometimes|required|in:published,draft', // Admin bisa mengubah status
        ]);

        $destination->update($validated);

        return response()->json($destination);
    }

    /**
     * Menghapus destinasi.
     */
    public function destroy(Destination $destination)
    {
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $destination->user_id) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.'], 403);
        }

        $destination->delete();

        return response()->json(['message' => 'Destinasi berhasil dihapus.']);
    }
}
