<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Proses login
    // Proses login
    public function loginAnggota(Request $request)
    {
        // Validasi input (email dan password)
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',  // Validasi email
            'password' => 'required|string', // Validasi password
        ]);

        // Jika Validasi Gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400); // Mengembalikan status error dengan kode 400
        }

        // Mencari pengguna berdasarkan email
        $user = \App\Models\Anggota::where('email', $request->email)->first(); // Cari berdasarkan email

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user); // Login user ke sistem

            // Mengecek role user dan mengarahkan sesuai role
            if ($user->role == 'admin') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login berhasil! Arahkan ke Dashboard.',
                    'redirect' => '/dashboard',  // Redirect ke dashboard untuk admin
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login berhasil! Arahkan ke halaman user.',
                    'redirect' => '/user',  // Redirect ke halaman user untuk role user
                ]);
            }
        }

        // Mengembalikan respons error jika email atau password salah
        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah!',
        ], 401); // Mengembalikan status error dengan kode 401 (Unauthorized)
    }




    // Tampilkan form login pengguna
    public function showLoginForm()
    {
        return view('login');
    }

    // Tampilkan form registrasi
    public function showRegisterFormAnggota()
    {
        return view('register');
    }

    // Proses registrasi
    // Proses registrasi
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:anggota,email', // Validasi email
            'password' => 'required|string|min:8|confirmed', // Menambahkan validasi untuk password_confirmation
            'role' => 'required|in:user,admin',
        ]);

        // Jika Validasi Gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(), // Mengambil pesan kesalahan pertama
            ], 400); // Mengembalikan status error dengan kode 400
        }

        // Membuat user baru
        $user = Anggota::create([
            'name' => $request->name,
            'email' => $request->email, // Simpan email
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => $request->role,
        ]);

        // Mengembalikan respons sukses setelah user berhasil dibuat
        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil! Silakan login.',
        ]);
    }
}
