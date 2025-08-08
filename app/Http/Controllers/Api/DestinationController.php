<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Menampilkan daftar semua destinasi.
     */
    public function index()
    {
        $destinations = Destination::latest()->get();
        return response()->json($destinations);
    }

    /**
     * Menyimpan destinasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ticket_price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $destination = $request->user()->destinations()->create($validated);

        return response()->json($destination, 201);
    }

    /**
     * Menampilkan satu destinasi spesifik.
     */
    public function show(Destination $destination)
    {
        return response()->json($destination);
    }

    /**
     * Mengupdate destinasi yang sudah ada.
     */
    public function update(Request $request, Destination $destination)
    {
        // Otorisasi: Pastikan user yang mengedit adalah pemilik destinasi.
        if ($request->user()->id !== $destination->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'ticket_price' => 'sometimes|required|integer',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $destination->update($validated);

        return response()->json($destination);
    }

    /**
     * Menghapus destinasi.
     */
    public function destroy(Request $request, Destination $destination)
    {
        // Otorisasi: Pastikan user yang menghapus adalah pemilik destinasi.
        if ($request->user()->id !== $destination->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $destination->delete();

        return response()->json(['message' => 'Destinasi berhasil dihapus.']);
    }
}
