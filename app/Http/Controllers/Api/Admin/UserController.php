<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna beserta perannya.
     */
    public function index()
    {
        // 'with('roles')' akan menyertakan data peran untuk setiap pengguna
        return User::with('roles')->latest()->get();
    }

    /**
     * Menyimpan pengguna baru (opsional, admin bisa membuat user baru).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name' // Memastikan peran yang dikirim valid
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return response()->json($user->load('roles'), 201);
    }

    /**
     * Menampilkan detail satu pengguna.
     */
    public function show(User $user)
    {
        return $user->load('roles');
    }

    /**
     * Mengupdate data pengguna (termasuk mengubah peran).
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'sometimes|required|string|exists:roles,name'
        ]);

        $user->update($validated);

        // Jika ada permintaan untuk mengubah peran, sinkronkan perannya
        if ($request->has('role')) {
            $user->syncRoles([$validated['role']]);
        }

        return response()->json($user->load('roles'));
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        // Tambahan keamanan: jangan biarkan admin menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus.']);
    }
}
