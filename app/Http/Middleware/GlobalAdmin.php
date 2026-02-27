<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  
public function handle(Request $request, Closure $next)
{
    $user = auth()->user();
    if (!$user || $user->global_role !== 'admin') {
        abort(403, 'Unauthorized');
    }
    return $next($request);
}
}
