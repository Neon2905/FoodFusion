<?php

namespace App\Http\Controllers;

use App\Models\Profile;

class FollowsController extends Controller
{
    public function store(Profile $profile)
    {
        request()->user()->following()->toggle($profile->id);
    }
}
