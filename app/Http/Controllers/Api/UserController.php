<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomUser;
use Illuminate\Http\Request;
use App\Http\Requests\UserCreateRequest;

class UserController extends Controller
{
    /**
     * Create a new user.
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create_user(UserCreateRequest $request)
    {
        // Create a new CustomUser instance with the validated request data
        $user = CustomUser::create($request->all());

        // Return a JSON response with a success message and the created user data
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201); // 201 (Created) status code
    }
}
