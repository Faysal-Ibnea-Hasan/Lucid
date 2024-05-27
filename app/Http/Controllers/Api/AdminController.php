<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;


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
    public function login_admin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $cookie = cookie('jwt', $success['token'], 60*24);


            return response()->json([
                'message'=> 'User login successfully.',
                $success,
            ])->withCookie($cookie);
        }
        else{
            return response()->json([
                'message'=> 'Unauthorized',

            ]);
        }
    }

    /**
     * Handle admin logout.
     *
     * @return \Illuminate\Http\JsonResponse - JSON response indicating successful logout.
     */
    public function logout_admin(Request $request)
    {
    //     // Remove the admin's email from the session
    //     Session::forget('admin_Id');
       $cookie = cookie()->forget('jwt');
       dd($cookie);
    // $request->user()->currentAccessToken()->delete();

        // Return a JSON response indicating successful logout
        return response()->json([
            'message' => 'Logged out!'
        ])->withCookie($cookie);
    }

    public function test(Request $request){
        $jwt = $request->cookie('jwt');
        return response()->json([
            'massage' => 'this is only for authenticated users',
            'token' => $jwt
        ]); //
    }
}
