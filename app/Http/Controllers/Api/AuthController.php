<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\TokenSesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // POST /api/auth/login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where(function ($q) use ($request) {
                $q->where('username', $request->username)
                  ->orWhere('email', $request->username);
            })
            ->where('activo', true)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Generar token
        $token = Str::random(64);

        TokenSesion::create([
            'usuario_id' => $usuario->id,
            'token'      => $token,
            'expira_at'  => now()->addDays(30),
        ]);

        return response()->json([
            'token' => $token,
            'usuario' => [
                'id'       => $usuario->id,
                'nombre'   => $usuario->nombre,
                'username' => $usuario->username,
                'email'    => $usuario->email,
                'rol'      => $usuario->rol,
            ],
        ]);
    }

    // POST /api/auth/logout
    public function logout(Request $request)
    {
        $token = $request->header('Authorization')
            ? str_replace('Bearer ', '', $request->header('Authorization'))
            : $request->header('X-Auth-Token');

        TokenSesion::where('token', $token)->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    // POST /api/auth/change-password
    public function changePassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password_nuevo'  => 'required|string|min:6',
        ]);

        $usuario = $request->get('_usuario');

        if (!Hash::check($request->password_actual, $usuario->password)) {
            return response()->json(['message' => 'La contraseña actual es incorrecta'], 422);
        }

        $usuario->password = Hash::make($request->password_nuevo);
        $usuario->save();

        return response()->json(['message' => 'Contraseña actualizada']);
    }

    // GET /api/auth/me
    public function me(Request $request)
    {
        $usuario = $request->get('_usuario');
        return response()->json([
            'id'       => $usuario->id,
            'nombre'   => $usuario->nombre,
            'username' => $usuario->username,
            'email'    => $usuario->email,
            'rol'      => $usuario->rol,
        ]);
    }
}
