<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    public function add(Request $request)
    {
        $validation = $request->validate([
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "visibility" => ["required", "in:public,private,off"],
        ]);

        $menu = Menu::create([
            "name" => $validation["name"],
            "description" => $validation["description"],
            "visibility" => $validation["visibility"],
        ]);

        return response()->json(
            [
                "message" => "Menu added successfully.",
                "data" => $menu,
            ],
            Response::HTTP_OK,
        );
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required"],
            "name" => ["sometimes", "string"],
            "description" => ["sometimes", "string"],
            "visibility" => ["sometimes", "in:public,private,off"],
        ]);

        $menu = Menu::find($validation["id"]);

        if (!$menu) {
            return response()->json(
                ["message" => "Menu does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $updateData = collect($validation)
            ->only(["name", "description", "visibility"])
            ->all();

        if (!empty($updateData)) {
            $menu->update($updateData);
        }

        return response()->json([
            "message" => "Menu updated successfully.",
            "data" => $menu,
        ]);
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required"],
        ]);

        $menu = Menu::find($validation["id"]);

        if (!$menu) {
            return response()->json(
                ["message" => "Menu does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $menu->delete();

        return response()->json(
            ["message" => "Menu deleted successfully."],
            Response::HTTP_OK,
        );
    }

    public function show(Request $request, ?string $id = null)
    {
        if ($id !== null) {
            $menu = Menu::with("contents")->find((int) $id);

            if (!$menu) {
                return response()->json(
                    ["message" => "Menu does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => $menu],
                Response::HTTP_OK,
            );
        }

        return response()->json(["data" => Menu::with("contents")->get()], Response::HTTP_OK);
    }
}
