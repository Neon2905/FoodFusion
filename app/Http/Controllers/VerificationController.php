<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        }
        $request->user()->sendEmailVerificationNotification();
        return view('auth.verify-email');
    }

    public function requestNotification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }

    public function verify(Request $request)
    {
        if (!URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired verification link.');
        }

        $userId = (int) $request->route('id');
        $hash = (string) $request->route('hash');

        $user = User::find($userId);
        if (!$user) {
            abort(404, 'User not found.');
        }

        // Match the hash against the user's email (same as Laravel’s default)
        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403, 'Invalid verification hash.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified(); // fires Verified event and sets email_verified_at
        }

        return redirect('/login')->with('status', 'Email verified!');
    }
}
