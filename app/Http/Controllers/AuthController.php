<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt(credentials: $credentials)) {
            return back()->withErrors([
                'error' => 'The provided credentials do not match our records.',
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();
        Auth::login($user, true);
        return redirect()->intended('/');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Registration successful! Please verify your email address.');
    }

    public function redirectToProvider($provider, Request $request)
    {
        $action = $request->query('action', 'login');
        session(['oauth_action' => $action]);
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider, Request $request)
    {
        $socialUser = Socialite::driver($provider)->user();

        $action = $request->session()->get('oauth_action');

        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($action === 'login') {
            if (!$user) {
                return back()->withErrors([
                    'error' => 'No account is linked with your provided login method. Please sign up first.',
                ]);
            }
            Auth::login($user, true);
            return redirect()->intended('/');
        } else if ($action === 'register' || $action === 'bind') {
            if ($user && $user->provider_id === $socialUser->getId()) {
                return back()->withErrors([
                    'error' => 'An account is already linked with your provided login method. Please log in instead.',
                ]);
            }

            // Check if user exists by email (for binding)
            $user = User::where('email', operator: $socialUser->getEmail())->first();
            if ($user) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            } else {
                $user = User::create([
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => now(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'password' => bcrypt(bin2hex(random_bytes(10))),
                ]);
                $user->profile()->create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'username' => 'user' . $user->id,
                    'profile' => $socialUser->getAvatar(),
                ]);
            }

            Auth::login($user, true);
            return redirect()->intended('/');
        } else {
            return back()->withErrors([
                'error' => 'Invalid action specified.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
