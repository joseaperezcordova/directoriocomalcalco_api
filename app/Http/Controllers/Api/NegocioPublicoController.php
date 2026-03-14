<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Categoria;
use Illuminate\Http\Request;

class NegocioPublicoController extends Controller
{
    // GET /api/categorias
    public function categorias()
    {
        $categorias = Categoria::where('activo', 1)->orderBy('nombre')->get(['id', 'nombre', 'icono']);
        return response()->json($categorias);
    }

    // GET /api/negocios
    // Parámetros opcionales: q, categoria_id, domicilio, abierto
    public function index(Request $request)
    {
        $query = Negocio::with(['categoria', 'horarios'])
            ->publicos()
            ->orderByRaw("FIELD(plan, 'premium', 'basico', 'gratis')")
            ->orderBy('nombre');

        // Búsqueda por texto
        if ($q = $request->get('q')) {
            if (strlen($q) < 3) {
                $query->where('nombre', 'LIKE', "%{$q}%");
            } else {
                $query->whereRaw(
                    'MATCH(nombre, descripcion) AGAINST(? IN BOOLEAN MODE)',
                    [$q . '*']
                );
            }
        }

        // Filtro por categoría
        if ($categoriaId = $request->get('categoria_id')) {
            $query->where('categoria_id', $categoriaId);
        }

        // Filtro por servicio a domicilio
        if ($request->get('domicilio')) {
            $query->where('servicio_domicilio', true);
        }

        $negocios = $query->paginate(20);

        return response()->json($negocios);
    }

    // GET /api/negocios/{id}
    public function show($id)
    {
        $negocio = Negocio::with(['categoria', 'horarios'])
            ->publicos()
            ->findOrFail($id);

        return response()->json($negocio);
    }
}
