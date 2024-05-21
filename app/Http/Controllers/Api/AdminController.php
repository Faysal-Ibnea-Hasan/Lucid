<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Services\ValidationServices;

class AdminController extends Controller
{
    public function create_admin(Request $request)
    {

        $data_validated = ValidationServices::admin_validation($request->all());
        if ($data_validated->fails()) {
            return response()->json(['errors' => $data_validated->errors()], 400);
        } else {
            $admin = Admin::create([
                'email' => $request->email,
                // 'password' => $request->password,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'massage' => 'Admin created successfully',
            ]);
        }
    }

    public function login_admin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $data = Admin::where('email', $email)->first();
        $hashPassword = Hash::check($password, $data->password);
        // dd($data);
        if ($data && $hashPassword) {
            Session::put(['admin_Id' => $data->email]);
            $lol2 = Session::get('admin_Id');
            // dd($lol2);
            return response()->json([
                'massage' => 'Admin authenticated',
                'session' => $lol2
            ]);

        } else {
            return response()->json([
                'massage' => 'Admin is not authenticated'
            ]);
        }
    }

    public function logout_admin()
    {
        Session::forget('admin_Id');
        return response()->json([
            'massage' => 'Logged out!'
        ]);
    }



}
