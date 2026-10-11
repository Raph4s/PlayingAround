<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/request-reset", [AuthController::class, "generateReset"]);
    Route::post("/validate-reset", [AuthController::class, "validateReset"]);
    Route::post("/reset", [AuthController::class, "resetUser"]);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post("/logout", [AuthController::class, "logout"]);
    });
});
