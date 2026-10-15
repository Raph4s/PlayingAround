<?php

namespace App\Http\Controllers;

use App\Models\MenuContent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuContentController extends Controller
{
    public function add(Request $request)
    {
        $validation = $request->validate([
            "name" => ["required", "string", "max:255"],
            "description" => ["required", "string"],
            "superset_id" => ["required", "string", "max:255"],
            "menu_id" => ["required", "integer", "exists:menus,id"],
            "visibility" => ["required", "in:public,private,off"],
        ]);

        $content = MenuContent::create($validation);

        return response()->json(
            [
                "message" => "Menu Content added successfully.",
                "data" => $content,
            ],
            Response::HTTP_OK,
        );
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required"],
            "name" => ["sometimes", "string", "max:255"],
            "description" => ["sometimes", "string"],
            "superset_id" => ["sometimes", "string", "max:255"],
            "menu_id" => ["sometimes", "integer", "exists:menus,id"],
            "visibility" => ["sometimes", "in:public,private,off"],
        ]);

        $content = MenuContent::find($validation["id"]);

        if (!$content) {
            return response()->json(
                ["message" => "Menu Content does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $updateData = collect($validation)
            ->only([
                "name",
                "description",
                "superset_id",
                "menu_id",
                "visibility",
            ])
            ->all();

        if (!empty($updateData)) {
            $content->update($updateData);
        }

        return response()->json([
            "message" => "Menu Content updated successfully.",
            "data" => $content,
        ]);
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required"],
        ]);

        $content = MenuContent::find($validation["id"]);

        if (!$content) {
            return response()->json(
                ["message" => "Menu Content does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $content->delete();

        return response()->json(
            ["message" => "Menu Content deleted successfully."],
            Response::HTTP_OK,
        );
    }

    public function show(Request $request, ?string $id = null)
    {
        if ($id !== null) {
            $content = MenuContent::with("menu")->find((int) $id);

            if (!$content) {
                return response()->json(
                    ["message" => "Menu Content does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => $content],
                Response::HTTP_OK,
            );
        }

        return response()->json(
            ["data" => MenuContent::with("menu")->get()],
            Response::HTTP_OK,
        );
    }
}
