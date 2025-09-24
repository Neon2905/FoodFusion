<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        // dd($user->profile ? $user->profile->getAttributes() : null);
        return view('profile.index', ['user' => $user, 'recipes' => $user->profile ? $user->profile->recipes : []]);
    }

    public function setup()
    {
        return view('profile.setup');
    }

    public function setup_submit(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'username' => ['required', 'string', 'max:50', 'unique:profiles,username'],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        $user = $request->user();

        $profile = $user->profile ?? new Profile();
        $profile->name = $validated['name'];
        $profile->username = $validated['username'];
        $profile->user_id = $user->id;
        $profile->bio = $validated['bio'] ?? '';

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $profile->profile = $path;
        }

        $profile->save();

        return redirect()->route('profile.show')->with('status', 'Profile updated!');
    }
}
