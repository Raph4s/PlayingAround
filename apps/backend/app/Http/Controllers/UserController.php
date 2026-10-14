<?php

namespace App\Http\Controllers;

use App\Mail\UserInvite;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function invite(Request $request)
    {
        $validation = $request->validate([
            "email" => ["required", "email", "disposable_email"],
            "role" => ["required", "integer", "exists:roles,id"],
            "inviter" => ["required", "integer", "exists:users,id"],
        ]);

        $findRole = Role::where(["id" => $validation["role"]])->exists();
        $findUser = User::where(["id" => $validation["inviter"]])->exists();

        if (!$findRole || !$findUser) {
            return response()->json(
                ["message" => "Invitation request is invalid."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $expire = CarbonImmutable::now()->add(1, "day");
        $id = str()->random(64);

        Invitation::create([
            "id" => $id,
            "email" => $validation["email"],
            "invited_by" => (int) $validation["inviter"],
            "role_id" => (int) $validation["role"],
            "expire_at" => $expire,
        ]);

        Mail::to($validation["email"])->queue(new UserInvite($id));

        return response()->json(
            ["message" => "Invitation has been sent."],
            Response::HTTP_OK,
        );
    }

    public function acceptInvitation(Request $request, string $invitationId)
    {
        // TODO: ngikut FE
        $validation = $request->validate([
            "id" => ["required", "email", "disposable_email"],
        ]);

        $invitation = Invitation::where("id", $invitationId)
            ->where("expire_at", ">", CarbonImmutable::now())
            ->first();

        if (!$invitation) {
            return response()->json(
                ["message" => "Invitation is invalid or expired."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $user = User::where("email", $invitation->email)->first();

        if ($user) {
            $user->roles()->attach($invitation->role_id);
            $invitation->update(["accepted_at" => CarbonImmutable::now()]);
        } else {
            $user = User::create([
                "email" => $invitation->email,
                "password" => str()->random(16),
            ]);

            $user->roles()->attach($invitation->role_id);
            $invitation->update(["accepted_at" => CarbonImmutable::now()]);
        }

        return response()->json(
            ["message" => "Invitation accepted. You can now login."],
            Response::HTTP_OK,
        );
    }
}
