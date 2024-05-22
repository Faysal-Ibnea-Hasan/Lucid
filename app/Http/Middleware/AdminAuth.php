<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request - The current HTTP request.
     * @param \Closure $next - The next middleware to call.
     * @return \Symfony\Component\HttpFoundation\Response - The response to the client.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the admin_Id is present in the session
        if (!Session::has('admin_Id')) {
            // If not present, return a JSON response indicating the user must be logged in
            return response()->json([
                'message' => 'You must be logged in!'
            ]);
        } else {
            // If present, proceed to the next middleware or request handler
            return $next($request);
        }
    }
}
