<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    // Cek Role Guru
    if ($user->role === 'guru') {
        return redirect()->route('dashboard.teacher')
            ->with('success', 'Login berhasil, selamat datang Guru');
    }

    // Cek Role Admin (TAMBAHKAN ELSEIF DI SINI)
    elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard')
            ->with('success', 'Login berhasil, selamat datang di Admin Dashboard');
    }

    // Default: Jika bukan Guru dan bukan Admin, lempar ke Student
    return redirect()->route('student.dashboard')
        ->with('success', 'Login berhasil, selamat datang');
}



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Logout berhasil');
    }
}
