<?php

use App\Http\Controllers\AssignRoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\SupersetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/request-reset", [AuthController::class, "generateReset"]);
    Route::post("/validate-reset", [AuthController::class, "validateReset"]);
    Route::post("/reset", [AuthController::class, "resetUser"]);
    Route::post("/register/invitation/{invitationId?}", [
        UserController::class,
        "acceptInvitation",
    ]);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post("/logout", [AuthController::class, "logout"]);
    });
});

// Superadmin-only management endpoints
Route::middleware(["auth:sanctum", "role:superadmin"])->group(function () {
    Route::prefix("app")->group(function () {
        Route::prefix("user")->group(function () {
            Route::post("/invite", [UserController::class, "invite"]);
        });

        Route::prefix("setting")->group(function () {
            Route::post("/preference", [PreferenceController::class, "add"]);
            Route::put("/preference", [PreferenceController::class, "update"]);
            Route::delete("/preference", [
                PreferenceController::class,
                "delete",
            ]);
            Route::get("/preference/{id?}", [
                PreferenceController::class,
                "show",
            ]);
        });
    });
});

// Operational features: accessible by Superadmin and Admin
Route::middleware(["auth:sanctum", "role:superadmin|admin"])->group(function () {
    Route::prefix("app/setting")->group(function () {
        Route::post("/superset", [SupersetController::class, "add"]);
        Route::put("/superset", [SupersetController::class, "update"]);
        Route::delete("/superset", [SupersetController::class, "delete"]);
        Route::get("/superset/{id?}", [SupersetController::class, "show"]);
    });
});
