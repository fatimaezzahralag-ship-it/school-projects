<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request)
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié',
            ], 401);
        }

        if ($role && $user->role !== $role) {
            return response()->json([
                'message' => 'Accès refusé - Rôle insuffisant',
                'role_requis' => $role,
                'role_utilisateur' => $user->role,
            ], 403);
        }

        return $next($request);
    }
}