<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Simpan ke Database (sesuai tabel users)
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'budget' => 0, // Default budget awal 0
        ]);

        // 3. Buat Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kirim Respon JSON ke Frontend
        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token, // Token ini yang dicari ApiService
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        // 1. Cek User berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // 2. Verifikasi Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // 3. Buat Token Baru
        // Hapus token lama agar bersih (opsional)
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:50|unique:users,email,'.$user->user_id .',user_id', // Ignore current user for unique check
            'password' => 'sometimes|confirmed|min:8',
            'current_password' => 'required_with:password',
            'preferensi' => 'sometimes|string', // JSON string for preferences
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Update Password if provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                 return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah.'
                ], 400);
            }
            $user->password = Hash::make($request->password);
        }

        // Update other fields
        if ($request->has('nama')) $user->nama = $request->nama;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('preferensi')) $user->preferensi = $request->preferensi;
        if ($request->has('no_telepon')) $user->no_telepon = $request->no_telepon;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }
}