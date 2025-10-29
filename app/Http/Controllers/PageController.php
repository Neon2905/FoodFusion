<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function community()
    {
        return view('community');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: persist to DB / send email. For now flash success.
        return back()->with('success', 'Thanks — we received your message and will respond shortly.');
    }

    public function culinaryResources()
    {
        $resources = Resource::query()
            ->where('published', true)
            ->where('category', 'culinary')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('culinary-resources', compact('resources'));
    }

    public function educationalResources()
    {
        $resources = Resource::query()
            ->where('published', true)
            ->where('category', 'educational')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('educational-resources', compact('resources'));
    }
}
