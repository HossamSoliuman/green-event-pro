<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->organization_id) {
            return redirect()->route('login')->with('error', 'No organization found. Please register or contact support.');
        }

        // Load organization and share with all views
        $organization = $user->organization;
        view()->share('currentOrganization', $organization);
        view()->share('currentUser', $user);

        return $next($request);
    }
}
