<?php

namespace Itpathsolutions\Sessionmanager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RoleBasedSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $role = auth()->user()->roles->first(); // Get the first assigned role

            if ($role) {
                Log::info("User Role:", ['role' => $role->name, 'session_lifetime' => $role->session_lifetime]);

                if (!empty($role->session_lifetime)) {
                    Config::set('session.lifetime', $role->session_lifetime);
                    Log::info("Session Lifetime Set:", ['session_lifetime' => config('session.lifetime')]);
                }
            } else {
                Log::warning("No role found for user: " . auth()->user()->id);
            }
        }

        return $next($request);
    }
}
