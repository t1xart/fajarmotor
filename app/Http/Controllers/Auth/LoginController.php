<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ── 1. VALIDASI INPUT (mencegah XSS & SQL Injection via mass input) ──
        $request->validate([
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'not_regex:/[<>\'";]/',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:128',
            ],
        ], [
            'email.not_regex' => 'Format email tidak valid.',
            'email.email' => 'Alamat email tidak valid.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // ── 2. SANITASI — strip semua tag HTML dari input ──
        $email = strip_tags(trim($request->input('email')));
        $password = $request->input('password'); // password tidak di-strip (bisa mengandung karakter khusus sah)

        // ── 3. CEGAH USER ENUMERATION — pesan error selalu sama ──
        $credentials = ['email' => $email, 'password' => $password];

        if (! Auth::attempt($credentials, false)) {
            Log::info('Failed login attempt', [
                'email' => $email,
                'ip' => $request->ip(),
                'ua' => $request->userAgent(),
            ]);

            // Pesan error generik — tidak memberi tahu apakah email ada atau tidak
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        // ── 4. CEK AKUN AKTIF ──
        if (! Auth::user()->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        // ── 5. SESSION REGENERATION — mencegah Session Fixation ──
        $request->session()->regenerate();

        // ── 6. LOG LOGIN SUKSES ──
        Log::info('Successful login', [
            'user_id' => Auth::id(),
            'email' => $email,
            'ip' => $request->ip(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();

        // ── Invalidate session & regenerate CSRF token ──
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', ['user_id' => $userId]);

        return redirect()->route('login');
    }
}
