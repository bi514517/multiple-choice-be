<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLogged
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (isset($user)) {
            return $next($request);
        } else {
            return abort(404);
        }
        
    }
}
