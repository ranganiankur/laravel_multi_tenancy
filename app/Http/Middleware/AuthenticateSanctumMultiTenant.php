<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateSanctumMultiTenant
{
    public function handle(Request $request, Closure $next)
    {
        $bearer = $request->bearerToken();

        if (!$bearer) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // First try to find token in central database
        tenancy()->end();

        $token = PersonalAccessToken::findToken($bearer);

        if ($token && $token->tokenable) {
            // User exists in central database
            $user = $token->tokenable;

            $request->setUserResolver(fn () => $user);
            // Ensure Auth uses the sanctum guard and has the resolved user so Spatie\Permission works
            Auth::shouldUse('sanctum');
            Auth::guard('sanctum')->setUser($user);

            return $next($request);
        }

        // If not found in central, try all tenant databases
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            $token = PersonalAccessToken::findToken($bearer);

            if ($token && $token->tokenable) {
                // User found in tenant database, keep tenant initialized
                $user = $token->tokenable;

                $request->setUserResolver(fn () => $user);
                Auth::shouldUse('sanctum');
                Auth::guard('sanctum')->setUser($user);

                return $next($request);
            }
        }

        // End tenancy if no token found
        tenancy()->end();

        return $next($request);
    }
}
