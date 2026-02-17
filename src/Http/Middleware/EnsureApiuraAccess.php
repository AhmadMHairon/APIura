<?php

namespace Apiura\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiuraAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production')) {
            abort(404);
        }

        $allowed = config('apiura.allowed_environments', ['local', 'staging', 'testing']);

        if (! in_array(app()->environment(), $allowed)) {
            abort(404);
        }

        return $next($request);
    }
}
