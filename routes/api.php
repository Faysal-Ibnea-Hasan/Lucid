<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Middleware\AdminAuth;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/create_admins',[AdminController::class,'create_admin']);
Route::post('/login_admins',[AdminController::class,'login_admin']);
Route::post('/get_sessions',[AdminController::class,'get_session'])->middleware(AdminAuth::class);
Route::post('/logout_admins',[AdminController::class,'logout_admin']);

