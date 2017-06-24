<?php

namespace Udoktor\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CheckProfilePicture
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->hasProfilePicture()) {
            $path = Storage::disk('public')->url('profile_pictures/' . Auth::user()->getProfilePicture());

            view()->share('profilePicture', $path);
        }
        return $next($request);
    }
}
