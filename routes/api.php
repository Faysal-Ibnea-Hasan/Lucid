<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Middleware\AdminAuth;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//================================================AdminController===============================================
Route::controller(AdminController::class)->group(function () {
    Route::post('/create_admins', 'create_admin');
    Route::post('/login_admins', 'login_admin');
    Route::post('/logout_admins','logout_admin');
});
