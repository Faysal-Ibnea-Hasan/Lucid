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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('admin_Id')) {
             return response()->json([
                'massage' => 'You must be logged in!'
            ]);
        }
        else{
            // return response()->json([
            //     'massage' => 'You are already logged in!'
            // ]);
            return $next($request);
        }
    }
}
