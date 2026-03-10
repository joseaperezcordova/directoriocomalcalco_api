<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller {

    // Muestra el login
    public function show() {
        return view('auth.login');
    }

    // PROCESA EL LOGIN
    public function login(Request $request) {
        // 1️⃣ Validación
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2️⃣ Intentar login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3️⃣ REDIRECCIÓN POR ROL ⭐
            $rol = auth()->user()->rol;

            switch ($rol) {
                case 'admin':
                    return redirect()->route('admin.dashboard');

               case 'seguridad':
                    return redirect()->route('seguridad.dashboard');

                case 'encargado':
                    return redirect()->route('encargado.dashboard');
                    
                case 'vendedor':
                    return redirect()->route('vendedor.dashboard');

                default:
                    Auth::logout();
                    return redirect('/login2');
            }
        }
        // 4️⃣ Error de login
        return back()->withErrors([
                    'email' => 'Las credenciales no son correctas.',
                ])->withInput();
    }

    // LOGOUT
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
