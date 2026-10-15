<?php

use App\Http\Controllers\AssignRoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuContentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupersetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Authentication endpoints
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
        // User management & invitations
        Route::prefix("user")->group(function () {
            Route::get("/", [UserController::class, "index"]);
            Route::post("/invite", [UserController::class, "invite"]);
            Route::get("/{id}", [UserController::class, "show"]);
            Route::put("/{id}", [UserController::class, "update"]);
            Route::delete("/{id}", [UserController::class, "destroy"]);

            // Assign / remove roles for users (AssignRoleController)
            Route::post("/role/assign", [AssignRoleController::class, "assign"]);
            Route::post("/role/remove", [AssignRoleController::class, "remove"]);
            Route::get("/{id}/roles", [AssignRoleController::class, "listUserRoles"]);
        });

        // Direct Role Assignment routes
        Route::prefix("role")->group(function () {
            Route::post("/assign", [AssignRoleController::class, "assign"]);
            Route::post("/remove", [AssignRoleController::class, "remove"]);
        });

        // Roles resource management
        Route::prefix("roles")->group(function () {
            Route::get("/", [RoleController::class, "index"]);
            Route::post("/", [RoleController::class, "store"]);
            Route::get("/{role}", [RoleController::class, "show"]);
        });

        // System preferences
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

        // Create Parent Menu / Category
        Route::prefix("dashboard")->group(function () {
            Route::post("/menu", [MenuController::class, "add"]);
        });
    });
});

// Operational features: accessible by Superadmin and Admin
Route::middleware(["auth:sanctum", "role:superadmin|admin"])->group(function () {
    Route::prefix("app")->group(function () {
        // Superset routes (under setting for frontend compatibility, and dashboard alias)
        Route::prefix("setting")->group(function () {
            Route::post("/superset", [SupersetController::class, "add"]);
            Route::put("/superset", [SupersetController::class, "update"]);
            Route::delete("/superset", [
                SupersetController::class,
                "delete",
            ]);
            Route::get("/superset/{id?}", [
                SupersetController::class,
                "show",
            ]);
        });

        Route::prefix("dashboard")->group(function () {
            // Superset alias under dashboard
            Route::post("/superset", [SupersetController::class, "add"]);
            Route::put("/superset", [SupersetController::class, "update"]);
            Route::delete("/superset", [
                SupersetController::class,
                "delete",
            ]);
            Route::get("/superset/{id?}", [
                SupersetController::class,
                "show",
            ]);

            // Menu (Categories / Parent) endpoints
            Route::put("/menu", [MenuController::class, "update"]);
            Route::delete("/menu", [MenuController::class, "delete"]);
            Route::get("/menu/{id?}", [MenuController::class, "show"]);

            // Menu Content (Dashboards) endpoints
            Route::post("/content", [MenuContentController::class, "add"]);
            Route::put("/content", [
                MenuContentController::class,
                "update",
            ]);
            Route::delete("/content", [
                MenuContentController::class,
                "delete",
            ]);
            Route::get("/content/{id?}", [
                MenuContentController::class,
                "show",
            ]);
        });
    });
});
