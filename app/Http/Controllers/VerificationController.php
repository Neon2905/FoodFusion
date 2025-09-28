<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        // ensure user is authenticated for the notice page
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login.form');
        }

        // compute remaining cooldown (seconds)
        $key = $this->cacheKey($user->id);
        $last = Cache::get($key);
        $cooldown = config('auth.verification_cooldown', 120);
        $remaining = 0;
        if ($last) {
            $elapsed = time() - $last;
            $remaining = $elapsed < $cooldown ? ($cooldown - $elapsed) : 0;
        }

        return view('auth.verify-email', [
            'user' => $user,
            'cooldown' => $remaining,
        ]);
    }

    public function requestNotification(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login.form');
        }

        $cooldown = config('auth.verification_cooldown', 60);
        $key = $this->cacheKey($user->id);
        $last = Cache::get($key);

        if ($last) {
            $elapsed = time() - $last;
            if ($elapsed < $cooldown) {
                $remaining = $cooldown - $elapsed;
                return back()->with('message', "Please wait {$remaining} second(s) before retrying.")->with('cooldown', $remaining);
            }
        }

        // send the verification email (uses the default notification)
        $user->sendEmailVerificationNotification();

        // mark last-sent time in cache for cooldown seconds
        Cache::put($key, time(), $cooldown);

        return back()->with('message', 'Verification link sent!')->with('cooldown', $cooldown);
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

        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403, 'Invalid verification hash.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified(); // fires Verified event and sets email_verified_at
        }

        return redirect('/')->with('status', 'Email verified!');
    }

    protected function cacheKey($userId)
    {
        return "verification_sent_ts_{$userId}";
    }
}
