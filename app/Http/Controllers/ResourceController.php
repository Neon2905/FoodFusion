<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function show($slug)
    {
        $resource = Resource::where('slug', $slug)->firstOrFail();
        return view('resource.detail', compact('resource'));
    }
}
