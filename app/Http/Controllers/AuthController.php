<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //
    // Fungsi untuk registrasi pengguna baru
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'in:mahasiswa,admin',  // Validasi role hanya 'mahasiswa' atau 'admin'
        ]);

        // Membuat user baru dengan default role 'mahasiswa' jika tidak ada role yang diberikan
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Hash password
            'role' => $request->role ?? 'mahasiswa',
        ]);

        // Membuat token autentikasi
        $token = $user->createToken('auth_token')->plainTextToken;

        // Mengembalikan token dan role
        return response()->json(['token' => $token, 'role' => $user->role], 201);
    }


    // Fungsi untuk login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Verifikasi password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Membuat token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Mengembalikan token
        return response()->json(['token' => $token], 200);
    }
}