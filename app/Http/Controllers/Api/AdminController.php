<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Http\Requests\AdminLoginRequest;

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

    /**
     * Handle admin login.
     *
     * @param AdminLoginRequest $request - Validated request containing the admin's email and password.
     * @return \Illuminate\Http\JsonResponse - JSON response indicating success or failure of authentication.
     */
    public function login_admin(AdminLoginRequest $request)
    {
        // Retrieve email and password from the request
        $email = $request->email;
        $password = $request->password;

        // Find the admin by email
        $admin = Admin::where('email', $email)->first();

        // Check if admin exists and the provided password matches the stored hashed password
        if ($admin && Hash::check($password, $admin->password)) {
            // Store the admin's email in session to indicate successful login
            Session::put('admin_Id', $admin->email);

            // Return a JSON response indicating successful authentication
            return response()->json([
                'message' => 'Admin authenticated',
                'session' => Session::get('admin_Id'),
            ], 200);
        }

        // Return a JSON response indicating invalid credentials
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * Handle admin logout.
     *
     * @return \Illuminate\Http\JsonResponse - JSON response indicating successful logout.
     */
    public function logout_admin()
    {
        // Remove the admin's email from the session
        Session::forget('admin_Id');

        // Return a JSON response indicating successful logout
        return response()->json([
            'message' => 'Logged out!'
        ]);
    }
}
