<?php

namespace App\Http\Controllers;

use App\Models\Review;
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

        return back()->with('status', 'Registration successful! Please verify your email address.');
    }

    public function changePassword(Request $request)
    {
        // TODO: Review
        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!password_verify($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'error' => 'The current password is incorrect.',
            ]);
        }

        // $user->update([
        //     'password' => bcrypt($validated['new_password']),
        // ]);

        return redirect()->back()->with('status', 'Password changed successfully.');
    }

    public function requestPasswordRecovery(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'We can\'t find a user with that email address.',
            ]);
        }

        // Manually send the reset link
        $token = app(abstract: 'auth.password.broker')->createToken($user);

        // You can customize the email here if needed
        // Mail::send('emails.password_reset', ['token' => $token, 'user' => $user], function ($message) use ($user) {
        //     $message->to($user->email);
        //     $message->subject('Reset Password Notification');
        // });

        return back()->with('status', "We have emailed your password reset link!\nToken: {$token}");
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
