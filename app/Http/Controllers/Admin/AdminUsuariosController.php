<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUsuariosController extends Controller {

    public function index() {
        return view('admin.usuarios.index');
    }

    public function tabla(Request $request) {
        $buscar = $request->get('buscar');
        $sort = $request->get('order', 'id');
        $dir = $request->get('dir', 'desc');

        $usuarios = User::when($buscar, function ($q) use ($buscar) {
                    $q->where('name', 'like', "%$buscar%");
                })
                ->orderBy($sort, $dir)
                ->paginate(5);

        return view('admin.usuarios.partials.tabla', compact(
                        'usuarios',
                        'sort',
                        'dir'
        ));
    }

    public function store(Request $request) {
        // 1️⃣ Validación
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'rol' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8'
        ]);

        // 2️⃣ Guardar en BD
        $usuario = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'rol' => $request->rol,
                    'password' => Hash::make($request->password)
        ]);

        // 3️⃣ Respuesta AJAX
        return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado correctamente',
                    'usuario' => $usuario
        ]);
    }

    public function update(Request $request, $id) {
        // 1️⃣ Validación
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $id,
            'rol' => 'required|string|max:255',
            'password' => 'nullable|confirmed|min:8'
        ]);

        // 2️⃣ Buscar usuario
        $usuario = User::findOrFail($id);

        // 3️⃣ Actualizar

        $data = $request->only(['name', 'email', 'rol']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        // 4️⃣ Respuesta AJAX
        return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado correctamente',
                    'usuario' => $usuario
        ]);
    }

}
