<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        string $role,
    ): Response {
        $user = $request->user();

        if (!$user || !$user->roles()->where("name", $role)->exists()) {
            return response()->json(
                ["message" => "Forbidden"],
                Response::HTTP_FORBIDDEN,
            );
        }

        return $next($request);
    }
}
