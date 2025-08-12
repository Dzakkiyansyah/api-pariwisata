<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Menyimpan (bookmark) sebuah destinasi untuk user yang sedang login.
     */
    public function store(Destination $destination)
    {
        $user = Auth::user();

        // 'attach' digunakan untuk relasi Many-to-Many
        // Ini akan menambahkan entri baru di tabel 'bookmarks'
        $user->bookmarks()->attach($destination->id);

        return response()->json(['message' => 'Destinasi berhasil disimpan.']);
    }

    /**
     * Menghapus bookmark dari sebuah destinasi untuk user yang sedang login.
     */
    public function destroy(Destination $destination)
    {
        $user = Auth::user();

        // 'detach' digunakan untuk menghapus entri di tabel perantara
        $user->bookmarks()->detach($destination->id);

        return response()->json(['message' => 'Destinasi berhasil dihapus dari daftar simpanan.']);
    }
}
