<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended('/admin');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'У вас нет прав доступа к админ-панели',
            ]);
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ]);
    }

    public function showRegisterForm()
    {
        // Проверяем, есть ли уже админы
        if (User::where('is_admin', true)->count() > 0) {
            return redirect('/admin/login')->with('error', 'Регистрация нового админа недоступна');
        }

        return view('admin.register');
    }

    public function register(Request $request)
    {
        if (User::where('is_admin', true)->count() > 0) {
            return redirect('/admin/login')->with('error', 'Регистрация нового админа недоступна');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => true
        ]);

        Auth::login($user);

        return redirect('/admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
