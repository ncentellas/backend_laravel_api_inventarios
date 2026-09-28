<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1/auth')->group(function () {
    Route::post("/login", [AuthController::class, "login"]);

    Route::post("/register", [AuthController::class, "register"]);

    Route::middleware(["auth:sanctum"])->group(function () {
        Route::get("/profile", [AuthController::class, "profile"]);

        Route::post("/logout", [AuthController::class, "logout"]);
    });
});
