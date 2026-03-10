<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Login - recibe email y password, devuelve token y datos del usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        // Generar token y guardarlo en remember_token
        $token = Str::random(60);
        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'token'   => $token,
            'user'    => [
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'rol'            => $user->rol,
                'id_punto_venta' => $user->id_punto_venta,
            ],
        ]);
    }

    /**
     * Logout - borra el token del usuario
     */
    public function logout(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $user->remember_token = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada',
        ]);
    }

    /**
     * Devuelve los datos del usuario autenticado
     */
    public function user(Request $request)
    {
        $user = $request->attributes->get('auth_user');

        return response()->json([
            'success' => true,
            'user'    => [
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'rol'            => $user->rol,
                'id_punto_venta' => $user->id_punto_venta,
            ],
        ]);
    }
}