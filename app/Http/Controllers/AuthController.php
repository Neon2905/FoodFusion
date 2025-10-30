<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // throttle key per email + ip
        $key = Str::lower($request->input('email')) . '|' . $request->ip();
        $maxAttempts = 3;
        $decaySeconds = 10 * 60; // 10 minutes

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'error' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        if (!Auth::attempt(credentials: $credentials)) {
            RateLimiter::hit($key, $decaySeconds);
            $attempts = RateLimiter::attempts($key);
            $remaining = max(0, $maxAttempts - $attempts);
            return back()->withErrors([
                'error' => "The provided credentials do not match our records. {$remaining} attempt(s) remaining.",
            ]);
        }

        // successful login -> clear attempts
        RateLimiter::clear($key);

        $user = User::where('email', $credentials['email'])->first();
        Auth::login($user, true);
        return redirect()->intended('/')->with('success', 'Logged in successfully.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'firstname' => ['string'],
            'lastname' => ['string']
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->profile()->create([
            'name' => trim(($validated['firstname'] ?? '') . ' ' . ($validated['lastname'] ?? '')),
            'username' => Str::slug(trim(($validated['firstname'] ?? 'user') . ' ' . ($validated['lastname'] ?? ''))) . $user->id,
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Registration successful! Please verify your email address.');
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
                    'auth_error' => 'No account is linked with your provided login method. Please sign up first.',
                ]);
            }
            Auth::login($user, true);
            return redirect()->intended('/');
        } else if ($action === 'register' || $action === 'bind') {
            if ($user && $user->provider_id === $socialUser->getId()) {
                return back()->withErrors([
                    'auth_error' => 'An account is already linked with your provided login method. Please log in instead.',
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
            return redirect()->intended('/')->with('success', 'Account linked and logged in successfully.');
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
