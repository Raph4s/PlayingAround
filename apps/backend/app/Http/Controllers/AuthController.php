<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\User;
use App\Models\UserReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken("api");

            return response()->json(
                [
                    "token" => $token->plainTextToken,
                    "user" => [
                        "id" => $user->id,
                        "email" => $user->email,
                        "fullname" => $user->fullname,
                        "roles" => $user->roles->pluck("name")->all(),
                    ],
                ],
                Response::HTTP_OK,
            );
        }

        return response()->json(
            [
                "message" => "Your email or password is incorrect",
            ],
            Response::HTTP_UNAUTHORIZED,
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(["message" => "Logged out"], Response::HTTP_OK);
    }

    public function generateReset(Request $request)
    {
        $credentials = $request->validate([
            "id" => ["nullable", "string", "size:64"],
            "email" => ["required", "email"],
        ]);

        $resetId = $credentials["id"] ?? null;
        $findId =
            $resetId !== null && UserReset::where("id", $resetId)->exists();

        if ($findId) {
            return response()->json(
                ["message" => "Your password reset request is still valid."],
                Response::HTTP_OK,
            );
        }

        $findEmail = User::where("email", $request->input("email"))->first();

        if ($findEmail) {
            $token = str()->random(64);
            $code = random_int(100000000, 999999999);
            $expire = CarbonImmutable::now()->add(30, "minutes");

            UserReset::create([
                "id" => $token,
                "user_id" => (int) $findEmail->id,
                "code" => (int) $code,
                "expire_at" => $expire,
            ]);

            Mail::to($findEmail->email)->queue(new ResetPassword($code));

            return response()->json(
                ["token" => $token],
                Response::HTTP_CREATED,
            );
        }

        return response()->json(
            ["message" => "Your email does not registered."],
            Response::HTTP_NOT_FOUND,
        );
    }

    public function validateReset(Request $request)
    {
        $credentials = $request->validate([
            "id" => ["required", "string", "size:64"],
            "code" => ["required", "string", "size:9"],
        ]);

        $findCode = UserReset::where([
            "id" => $credentials["id"],
            "code" => (int) $credentials["code"],
        ])
            ->where("expire_at", ">", CarbonImmutable::now())
            ->exists();

        if ($findCode) {
            $token = str()->random(64);

            UserReset::where("id", $credentials["id"])
                ->where("expire_at", ">", CarbonImmutable::now())
                ->update([
                    "token" => $token,
                ]);

            return response()->json(
                [
                    "token" => $token,
                ],
                Response::HTTP_CREATED,
            );
        }

        return response()->json(
            [
                "message" => "Your password reset request is invalid.",
            ],
            Response::HTTP_NOT_FOUND,
        );
    }

    public function resetUser(Request $request)
    {
        $token = $request->bearerToken();

        if ($token === null || strlen($token) !== 64) {
            return response()->json(
                [
                    "message" => "Your password reset request is invalid.",
                ],
                Response::HTTP_NOT_FOUND,
            );
        }

        $credentials = $request->validate([
            "id" => ["required", "string", "size:64"],
            "password" => ["required", "string"],
        ]);

        $checkReset = UserReset::where([
            "id" => $credentials["id"],
            "token" => $token,
        ])
            ->where("expire_at", ">", CarbonImmutable::now())
            ->first();

        if (!$checkReset) {
            return response()->json(
                ["message" => "Your password reset request is invalid."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $user = User::find($checkReset->user_id);

        if (!$user) {
            return response()->json(
                ["message" => "Your password reset request is invalid."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $user->password = $credentials["password"];
        $user->save();
        $checkReset->delete();

        return response()->json(
            [
                "message" => "Your password is successfully applied.",
            ],
            Response::HTTP_OK,
        );
    }
}
