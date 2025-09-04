<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // Attempt to log the user in
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'error' => 'The provided credentials do not match our records.',
            ]);
        }

        // Authentication successful
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    public function register(Request $request)
    {
        return $this->signup($request);
    }

    public function signup(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users.email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Create the user
        $user = \App\Models\User::create([
            'name' => 'John', // TODO
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Log the user in
        Auth::login($user);

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
