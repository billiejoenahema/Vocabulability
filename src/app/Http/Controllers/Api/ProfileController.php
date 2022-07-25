<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return ProfileResource
     */
    public function __invoke(): ProfileResource
    {
        return new ProfileResource(auth()->user());
    }
}
