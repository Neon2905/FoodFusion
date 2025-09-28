<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        // TODO: Review
        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!password_verify($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'error' => 'The current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => bcrypt($validated['new_password']),
        ]);

        return back()->with('status', 'Password changed successfully.');
    }

    public function requestRecovery(Request $request)
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

        $status = Password::broker()->sendResetLink(
            ['email' => $user->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }

    public function showRecoverySubmitForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            abort(404);
        }

        return view('auth.recover-password.submit', ['token' => $token, 'email' => $email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PasswordReset
            ? back()->with('status', __($status))
            : back()->withErrors(provider: ['email' => [__($status)]]);
    }
}
