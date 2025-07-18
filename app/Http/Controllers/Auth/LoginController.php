<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $role = Auth::user()->role->name ?? '';
            if ($role === 'headmaster') {
                return redirect()->intended('/dashboard/headmaster');
            } elseif (in_array($role, ['it', 'it_department', 'it-staff', 'it_department_user'])) {
                return redirect()->intended('/dashboard/it');
            } elseif ($role === 'teacher') {
                return redirect()->intended('/dashboard/teacher');
            }
            return redirect()->intended('/dashboard/student');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }
} 