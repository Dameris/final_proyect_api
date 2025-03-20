<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return Inertia::render('Auth/Login');
    }

    public function showSignupForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return Inertia::render('Auth/Signup');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'The credentials are incorrect']);
    }

    public function signup(SignupRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            "email" => $validated["email"],
            "password" => bcrypt($validated["password"]),
            "first_name" => $validated["first_name"],
            "last_name" => $validated["last_name"],
            "gender" => $validated["gender"],
            "name" => $validated["first_name"] . " " . $validated["last_name"]
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }

    public function checkSession(Request $request)
    {
        $session = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->first();

        return response()->json([
            'isAuthenticated' => $session !== null,
            'user' => Auth::user()
        ]);
    }
}
