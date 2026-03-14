<?php

namespace App\Http\Middleware;

use App\Models\TokenSesion;
use Closure;
use Illuminate\Http\Request;

class AuthToken
{
    public function handle(Request $request, Closure $next, string $rolRequerido = null)
    {
        // Aceptar token en header Authorization: Bearer xxx  o  X-Auth-Token: xxx
        $token = null;

        if ($auth = $request->header('Authorization')) {
            $token = str_replace('Bearer ', '', $auth);
        } elseif ($xToken = $request->header('X-Auth-Token')) {
            $token = $xToken;
        }

        if (!$token) {
            return response()->json(['message' => 'Token requerido'], 401);
        }

        $sesion = TokenSesion::with('usuario')
            ->where('token', $token)
            ->where('expira_at', '>', now())
            ->first();

        if (!$sesion || !$sesion->usuario) {
            return response()->json(['message' => 'Token inválido o expirado'], 401);
        }

        $usuario = $sesion->usuario;

        if (!$usuario->activo) {
            return response()->json(['message' => 'Usuario inactivo'], 403);
        }

        // Verificar rol si se especificó
        if ($rolRequerido && $usuario->rol !== $rolRequerido) {
            return response()->json(['message' => 'Sin permisos suficientes'], 403);
        }

        // Pasar usuario al request para usarlo en los controladores
        $request->attributes->set('_usuario', $usuario);

        return $next($request);
    }
}
