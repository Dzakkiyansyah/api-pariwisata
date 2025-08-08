<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MitraApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle user registration request.
     */
    public function register(Request $request)
    {
        // Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Berikan Peran "wisatawan" secara otomatis
        $user->assignRole('wisatawan');

        // Buat Token API (menggunakan Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kembalikan Respon Sukses
        return response()->json([
            'message' => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    /**
     * Handle user login request.
     */
    public function login(Request $request)
    {
        // Validasi Input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Coba Lakukan Otentikasi universal
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Kredensial tidak valid'], 401);
        }

        // Jika berhasil, ambil data user
        $user = User::where('email', $request['email'])->firstOrFail();

        // Buat Token API
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kembalikan Respon Sukses
        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
    /**
     * Handle Registrasi Pengelola
     */
    public function registrasiPengelola(Request $request)
    {
        // validasi Input dan file
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'required|string|max:255',
            'official_document' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // Berikaan peran "pengelola"
        $user->assignRole('pengelola');

        // Simpan Document yang di unggah
        $documentPath = $request->file('official_document')->store('documents','public');

        // Buat entri baru di tabel aplikasi mitra
        MitraApplication::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'status' => 'pending', // status awal
            'official_document_path' => $documentPath,
        ]);
        // kembalikan respon Sukses
        return response()->json([
            'message' => 'Registrasi pengelola berhasil, Akun anda sedang dalam proses verifikasi.',
            'user'=> $user,
        ], 201);
    }
}
