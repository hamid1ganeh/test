<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class UserActivity
{

    public function handle(Request $request, Closure $next)
    {
        if(auth()->check())
        {
            $user = auth()->user();
            Cache::remember("user-{$user->id}-staus", 10,function(){
                return 'online';
            });
        }

        return $next($request);
    }
}
