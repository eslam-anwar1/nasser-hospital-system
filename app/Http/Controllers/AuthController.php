<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
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
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني الطبي.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attempt = Auth::attempt([
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if ($attempt) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'login' => 'هذا الحساب الطبي معطل حالياً. يرجى التواصل مع إدارة النظام.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'أهلاً بك يا ' . $user->name . ' في مجمع ناصر الطبي.');
        }

        return back()->withErrors([
            'login' => 'بيانات الاعتماد غير متطابقة مع سجلات موظفي مجمع ناصر الطبي.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح من نظام مجمع ناصر الطبي.');
    }
}
