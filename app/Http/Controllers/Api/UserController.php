<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomUser;
use Illuminate\Http\Request;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Storage;
use Auth;


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
        $user = new CustomUser();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->password = $request->password;
        $user->nid = $request->nid;
        $user->address = $request->address;
        $user->thana = $request->thana ?? 'not selected';
        $user->zilla = $request->zilla ?? 'not selected';
        $user->district = $request->district ?? 'not selected';
        $user->division = $request->division ?? 'not selected';
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('users');
            $fileUrl = Storage::url($filePath);
            $user->image = $fileUrl;
        } else {
            $user->image = 'not uploaded';
        }
        $user->subscription = $request->subscription ?? 'no package';
        $user->save();

        // Return a JSON response with a success message and the created user data
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201); // 201 (Created) status code
    }
    public function login_user(UserLoginRequest $request)
    {
        if (Auth::guard('customuser')->attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
            $user = Auth::guard('customuser')->user();
            $token = $user->createToken('user')->plainTextToken;
            $cookie = cookie('jwt', $token, 60 * 24); // 1 day


            return response()->json([
                'message' => 'User login successfully.',
                $token,
            ], 200)->withCookie($cookie);
            // return response()->json([
            //     'message' => 'Authentication successful',

            // ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized',

            ], 401);
        }
    }
}
