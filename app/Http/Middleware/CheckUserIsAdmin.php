<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && Auth::user()->type == 'admin')
        {
            return $next($request);
        }

       return back();
    }
}
