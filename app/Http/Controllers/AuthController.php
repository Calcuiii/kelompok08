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
        $user = \App\Models\Anggota::where('name', $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user); // Login user ke sistem

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('status', 'Login berhasil sebagai Admin!');
            } elseif ($user->role === 'user') {
                return redirect()->route('user')->with('status', 'Login berhasil sebagai User!');
            }

            return back()->withErrors(['name' => 'Name atau password salah!'])->withInput();
        }
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
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',  // Validasi role
        ]);

        // Jika Validasi Gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Membuat user baru
        $user = Anggota::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Redirect ke halaman login atau halaman lain
        return redirect()->route('login.form')->with('status', 'Registrasi berhasil!');
    }
}
