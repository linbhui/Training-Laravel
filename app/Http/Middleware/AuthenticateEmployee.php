<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('employees')->check()) {
            return redirect()->route('management.showLogin');
        }

        return $next($request);
    }
}
