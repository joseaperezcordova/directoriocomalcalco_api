<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token requerido',
            ], 401);
        }

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o sesión expirada',
            ], 401);
        }

        // Pasar el usuario al request para usarlo en los controladores
        $request->attributes->set('auth_user', $user);

        return $next($request);
    }
}