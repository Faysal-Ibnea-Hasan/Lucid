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
       $user = new CustomUser();
       $user->name = $request->name;
       $user->mobile = $request->mobile;
       $user->password = $request->password;
       $user->nid = $request->nid;
       $user->address = $request->address;
       $user->thana = $request->thana ?? 'not selected';
       $user->zilla = $request->zilla ?? 'not selected' ;
       $user->district = $request->district ?? 'not selected';
       $user->division = $request->division ?? 'not selected';
       $user->image = $request->file('image')->store('users') ?? 'not uploaded';
       $user->subscription = $request->subscription ?? 'no package';
        $user->save();

        // Return a JSON response with a success message and the created user data
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201); // 201 (Created) status code
    }
}
