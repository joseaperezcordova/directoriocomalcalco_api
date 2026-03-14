<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // GET /api/admin/negocios
    public function negocios(Request $request)
    {
        $query = Negocio::with(['categoria', 'horarios', 'capturista'])
            ->orderBy('created_at', 'desc');

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }

        return response()->json($query->paginate(20));
    }

    // GET /api/admin/usuarios
    public function usuarios()
    {
        $usuarios = Usuario::orderBy('nombre')->get(['id', 'nombre', 'username', 'email', 'rol', 'activo', 'created_at']);
        return response()->json($usuarios);
    }

    // POST /api/admin/usuarios
    public function crearUsuario(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:150',
            'username' => 'nullable|string|max:150|unique:usuarios,username',
            'email'    => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol'      => 'required|in:admin,capturista',
        ]);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => $request->rol,
            'activo'   => true,
        ]);

        return response()->json($usuario->only(['id', 'nombre', 'username', 'email', 'rol', 'activo']), 201);
    }

    // PUT /api/admin/usuarios/{id}
    public function editarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre'   => 'sometimes|required|string|max:150',
            'username' => 'nullable|string|max:150|unique:usuarios,username,' . $id,
            'email'    => 'sometimes|required|email|unique:usuarios,email,' . $id,
            'password' => 'nullable|string|min:6',
            'rol'      => 'sometimes|required|in:admin,capturista',
            'activo'   => 'sometimes|boolean',
        ]);

        $datos = $request->only(['nombre', 'username', 'email', 'rol', 'activo']);

        if (!empty($request->password)) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return response()->json($usuario->fresh()->only(['id', 'nombre', 'username', 'email', 'rol', 'activo']));
    }

    // DELETE /api/admin/usuarios/{id}
    public function desactivarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['activo' => false]);

        return response()->json(['message' => 'Usuario desactivado']);
    }

    // GET /api/admin/categorias
    public function categorias()
    {
        $categorias = Categoria::orderBy('nombre')->get(['id', 'nombre', 'icono', 'activo']);
        return response()->json($categorias);
    }

    // POST /api/admin/categorias
    public function crearCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'icono'  => 'nullable|string|max:50',
        ]);

        $categoria = Categoria::create([
            'nombre' => $request->nombre,
            'icono'  => $request->icono,
            'activo' => true,
        ]);

        return response()->json($categoria, 201);
    }

    // PUT /api/admin/categorias/{id}
    public function editarCategoria(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'icono'  => 'nullable|string|max:50',
        ]);

        $categoria->update($request->only(['nombre', 'icono']));

        return response()->json($categoria);
    }

    // PATCH /api/admin/categorias/{id}/toggle
    public function toggleCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->update(['activo' => !$categoria->activo]);

        return response()->json(['id' => $categoria->id, 'activo' => $categoria->activo]);
    }
}
