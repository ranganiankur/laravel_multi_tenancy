<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class InitializeTenantFromHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID');

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                // tenancy()->initialize($tenant);

                config(['app.current_tenant' => $tenant]);
                $request->merge(['tenant' => $tenant]);
            }
        }

        return $next($request);
    }
}
