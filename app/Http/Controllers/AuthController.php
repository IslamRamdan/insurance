<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VisaRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $visaUser = User::where('email', $request->email)->first();

        if ($visaUser && Hash::check($request->password, $visaUser->password)) {
            // تخزين البيانات في session
            $request->session()->put('visa_user', $visaUser->id);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email or password is incorrect.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('visa_user');
        return redirect()->route('login');
    }

    public function dashboard(Request $request)
    {
        // التحقق من تسجيل الدخول
        if (!$request->session()->has('visa_user')) {
            return redirect()->route('login');
        }

        $visaRequests = VisaRequest::all();
        return view('dashboard', compact('visaRequests'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required|string',
            'password' => 'required|string|min:4',
        ]);

        User::create([
            'email' => $request->email,
            'name' => $request->username,
            'password' => Hash::make($request->password), // 🔑 تخزين مشفّر
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully.');
    }
}
