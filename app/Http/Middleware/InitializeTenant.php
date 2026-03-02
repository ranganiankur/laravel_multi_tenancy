<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;

class InitializeTenant
{
    public function handle($request, Closure $next)
    {
        if ($request->tenant_id) {
            $tenant = Tenant::find($request->tenant_id);

            if ($tenant) {
                tenancy()->initialize($tenant);
            }
        }

        return $next($request);
    }
}
