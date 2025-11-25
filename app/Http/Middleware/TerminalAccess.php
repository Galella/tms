<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TerminalAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $terminal = $request->route('terminal');

        if (!Auth::user()->terminals->contains($terminal)) {
            abort(403, 'Akses terminal tidak diizinkan');
        }

        return $next($request);
    }
}
