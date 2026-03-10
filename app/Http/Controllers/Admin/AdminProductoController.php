<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class AdminProductoController extends Controller {

    public function index() {
        return view('admin.productos.index');
    }

    public function tabla(Request $request) {
        $buscar = $request->get('buscar');
        $sort = $request->get('order', 'id');
        $dir = $request->get('dir', 'desc');

        $productos = Producto::when($buscar, function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%$buscar%");
                })
                ->orderBy($sort, $dir)
                ->paginate(5);

        return view('admin.productos.partials.tabla', compact(
                        'productos',
                        'sort',
                        'dir'
        ));
    }

    public function store(Request $request) {
        // 1️⃣ Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // 2️⃣ Guardar en BD
        $producto = Producto::create([
                    'nombre' => $request->nombre,
                    'precio' => $request->precio,
                    'stock' => $request->stock,
        ]);

        // 3️⃣ Respuesta AJAX
        return response()->json([
                    'success' => true,
                    'message' => 'Producto creado correctamente',
                    'producto' => $producto
        ]);
    }

    public function update(Request $request, $id) {
        // 1️⃣ Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // 2️⃣ Buscar producto
        $producto = Producto::findOrFail($id);

        // 3️⃣ Actualizar
        $producto->update([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
        ]);

        // 4️⃣ Respuesta AJAX
        return response()->json([
                    'success' => true,
                    'message' => 'Producto actualizado correctamente',
                    'producto' => $producto
        ]);
    }

}
