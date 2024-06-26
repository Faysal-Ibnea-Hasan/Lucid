<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */


    public function handle($request, Closure $next, ...$guards)
    {
        $jwt = $request->cookie('jwt');
        if ($jwt) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
            $this->authenticate($request, $guards);

            return $next($request);
        }
        else{
            return response()->json([
                'message' =>'Jwt not found',
            ],401);
        }

    }
}
