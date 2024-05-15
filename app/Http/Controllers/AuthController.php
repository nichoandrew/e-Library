<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\mahasiswa;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->login_as === "none") {
            return redirect()->route('login');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($request->login_as === "admin") {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard_admin');
            }
        } elseif ($request->login_as === "mahasiswa") {
            if (Auth::guard('mahasiswa')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard_mahasiswa');
            }
        }

        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_status' => 'required|in:admin,mahasiswa',
        ]);

        $hashedPassword = Hash::make($request->input('password'));

        if ($request->input('role_status') === 'admin') {
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $hashedPassword,
                'role_status' => $request->input('role_status'),
            ]);
        } elseif ($request->input('role_status') === 'mahasiswa') {
            mahasiswa::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $hashedPassword,
                'kelas' => $request->input('kelas'),
                'role_status' => $request->input('role_status'),
            ]);
        }

        return redirect()->route('login');
    }
}
