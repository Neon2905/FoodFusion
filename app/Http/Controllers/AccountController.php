<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class AccountController extends Controller
{
    public function settings(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile ?? null;
        return view('account.settings', compact('user', 'profile'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile ?? $user->profile()->create([]);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|alpha_dash|max:50|unique:profiles,username,' . ($profile->id ?? 'NULL'),
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        // handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('profiles/' . $user->id, $filename, 'public');
            $publicUrl = '/storage/' . ltrim($path, '/');
            $profile->profile = $publicUrl; // existing codebase expects profile->profile as avatar url
        }

        $profile->name = $request->input('name');
        $profile->username = $request->input('username');
        $profile->bio = $request->input('bio');
        $profile->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
