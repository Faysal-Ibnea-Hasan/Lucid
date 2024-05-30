<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Cookie;

// use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    /**
     * Create a new admin user.
     *
     * @param AdminLoginRequest $request - Validated request containing the admin's email and password.
     * @return \Illuminate\Http\JsonResponse - JSON response with a success message and the created admin data.
     */
    public function create_admin(AdminLoginRequest $request)
    {
        // Create a new admin with the provided email and hashed password
        $admin = Admin::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Return a JSON response indicating successful creation
        return response()->json([
            'message' => 'Admin created successfully',
            'data' => $admin
        ], 201);
    }
    public function update_admin(Request $request)
    {
        try {
            $find_admin = Admin::findOrFail($request->id);
            $update_admin = $find_admin->update([
                'email' => $request->email ?? $find_admin->email,
                'role' => $request->role ?? $find_admin->role,
                'status' => $request->status ?? $find_admin->status
            ]);
            return response()->json([
                'message' => 'Admin updated successfully',
                'data' => $update_admin
            ], 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        }

    }

    /**
     * Handle admin login.
     *
     * @param AdminLoginRequest $request - Validated request containing the admin's email and password.
     * @return \Illuminate\Http\JsonResponse - JSON response indicating success or failure of authentication.
     */
    // public function login_admin(AdminLoginRequest $request)
    // {
    //     // Retrieve email and password from the request
    //     $email = $request->email;
    //     $password = $request->password;

    //     // Find the admin by email
    //     $admin = Admin::where('email', $email)->first();

    //     // Check if admin exists and the provided password matches the stored hashed password
    //     if ($admin && Hash::check($password, $admin->password)) {
    //         // Store the admin's email in session to indicate successful login
    //         Session::put('admin_Id', $admin->email);
    //         $auth_admin = Auth::user();
    //         $token =  $auth_admin->createToken('lucid')->plainTextToken;
    //         $cookie = cookie('jwt', $token, 60*24);
    //         // Return a JSON response indicating successful authentication
    //         return response()->json([
    //             'message' => 'Admin authenticated',
    //             'session' => Session::get('admin_Id'),
    //             'token' => $token
    //         ], 200)->withCookie($cookie);
    //     }

    //     // Return a JSON response indicating invalid credentials
    //     return response()->json([
    //         'message' => 'Invalid credentials',
    //     ], 401);
    // }
    public function login_admin(AdminLoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken($user->role)->plainTextToken;
            $cookie = cookie('jwt', $success['token'], 60 * 24);
            
            return response()->json([
                'message' => 'User login successfully.',
                $success,
            ],200)->withCookie($cookie);
        } else {
            return response()->json([
                'message' => 'Unauthorized',

            ],401);
        }
    }

    /**
     * Handle admin logout.
     *
     * @return \Illuminate\Http\JsonResponse - JSON response indicating successful logout.
     */
    public function logout_admin(Request $request)
    {
        $cookie = Cookie::forget('jwt');

        // Return a JSON response indicating successful logout
        return response()->json([
            'message' => 'Logged out!'
        ])->withCookie($cookie);
    }

    public function test(Request $request)
    {
        $jwt = $request->cookie('jwt');
        return response()->json([
            'massage' => 'this is only for authenticated users',
            'token' => $jwt
        ]); //
    }
}
