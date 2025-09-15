<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Profile;
use Laravel\Socialite\Facades\Socialite;

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
        if (!Auth::attempt(credentials: $credentials)) {
            return back()->withErrors([
                'error' => 'The provided credentials do not match our records.',
            ]);
        }

        // Authentication successful
        $user = User::where('email', $credentials['email'])->first();
        Auth::login($user, true);
        return redirect()->intended('/recipes');
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

        return redirect('/email/verify');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (!$user && $socialUser->getEmail()) {
            $user = User::where('email', $socialUser->getEmail())->first();
        }

        if (!$user) {
            $user = User::create([
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'password' => bcrypt(bin2hex(random_bytes(10))), // random password
            ]);

            $user->profile->update([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'profile' => $socialUser->getAvatar(),
            ]);
        } else {
            // TODO: Decide if this is necessary
            // Update provider fields if missing
            $user->update([
                'provider' => $user->provider ?? $provider,
                'provider_id' => $user->provider_id ?? $socialUser->getId(),
            ]);
        }

        Auth::login($user, true);

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
