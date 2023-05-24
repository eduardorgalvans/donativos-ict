<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        # verificamo que este firmado en el sistema
        if (Auth::check()) {
            # generamso la expiracion de el cache
            $tExpiresAt = Carbon::now()->addMinutes(5);
            # generamso el cahce del usarios logueado
            Cache::put('user-online-'.Auth::user()->id, true, $tExpiresAt);
        }
        return $next($request);
    }
}
