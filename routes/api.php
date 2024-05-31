<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\Authenticate;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//================================================AdminController===============================================
Route::controller(AdminController::class)->group(function () {
    Route::post('/create_admins', 'create_admin');
    Route::post('/login_admins', 'login_admin');
    Route::post('/update_admins', 'update_admin');
    Route::post('/logout_admins','logout_admin')->middleware(Authenticate::class);
    Route::post('/test','test')->middleware(Authenticate::class);
});
//================================================UserController===============================================
Route::controller(UserController::class)->group(function () {
    Route::post('/create_users', 'create_user');
    Route::post('/login_users', 'login_user');

});
//================================================CategoryController===============================================
Route::controller(CategoryController::class)->group(function () {
    Route::post('/create_categories', 'create_category');
    Route::post('/update_categories', 'update_category');
    Route::post('/delete_categories', 'delete_category');


});
