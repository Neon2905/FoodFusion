<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('culinary-resources');
    }

    public function educationalResources()
    {
        return view('educational-resources');
    }
}
