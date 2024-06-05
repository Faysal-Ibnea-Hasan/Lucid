<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomUser;
use Illuminate\Http\Request;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Storage;
use Auth;
use Log;


class UserController extends Controller
{
    /**
     * Creates a new user based on the provided request data.
     *
     * @param UserCreateRequest $request The request object containing the user data.
     * @return \Illuminate\Http\JsonResponse A JSON response with a success message and the created user data.
     */
    public function create_user(UserCreateRequest $request)
    {
        // Instantiate a new CustomUser model.
        $user = new CustomUser();

        // Set the user properties from the request data.
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->password = $request->password;
        $user->nid = $request->nid;
        $user->address = $request->address;

        // Set optional properties with default values if not provided.
        $user->thana = $request->thana ?? 'not selected';
        $user->zilla = $request->zilla ?? 'not selected';
        $user->district = $request->district ?? 'not selected';
        $user->division = $request->division ?? 'not selected';

        // Handle file upload if an image is provided.
        if ($request->hasFile('image')) {
            // Store the uploaded image in the 'users' directory.
            $filePath = $request->file('image')->store('users');
            // Get the URL of the stored image.
            $fileUrl = Storage::url($filePath);
            // Set the user's image URL.
            $user->image = $fileUrl;
        } else {
            // Set a default value if no image is uploaded.
            $user->image = 'not uploaded';
        }

        // Set the subscription status with a default value if not provided.
        $user->subscription = $request->subscription ?? 'no package';

        // Save the user data to the database.
        $user->save();

        // Return a JSON response with a success message and the created user data.
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201); // 201 (Created) status code
    }

    /**
     * Authenticates a user based on the provided login credentials.
     *
     * @param UserLoginRequest $request The request object containing the login credentials.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success or failure of the login attempt.
     */
    public function login_user(UserLoginRequest $request)
    {
        // Attempt to authenticate the user using the custom user guard.
        if (Auth::guard('customuser')->attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
            // Retrieve the authenticated user.
            $user = Auth::guard('customuser')->user();

            // Create a new personal access token for the user.
            $token = $user->createToken('user')->plainTextToken;

            // Create a cookie to store the JWT with a lifespan of 1 day.
            $cookie = cookie('jwt', $token, 60 * 24); // 60 minutes * 24 hours

            // Return a JSON response with a success message and the token, attaching the cookie.
            return response()->json([
                'message' => 'User login successfully.',
                'token' => $token,
            ], 200)->withCookie($cookie);

        } else {
            // Return a JSON response with an error message if authentication fails.
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
    }

}
