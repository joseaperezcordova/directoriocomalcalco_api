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
    // Parámetros opcionales: q, categoria_id, domicilio, lat, lng, radio (km, default 5)
    public function index(Request $request)
    {
        $lat   = $request->get('lat');
        $lng   = $request->get('lng');
        $radio = (float) ($request->get('radio', 5));
        $usarGeo = is_numeric($lat) && is_numeric($lng);

        if ($usarGeo) {
            // Haversine en MySQL — incluye distancia_km en cada fila
            $haversine = '(6371 * ACOS(
                COS(RADIANS(?)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS(?)) +
                SIN(RADIANS(?)) * SIN(RADIANS(lat))
            ))';

            $query = Negocio::with(['categoria', 'horarios'])
                ->publicos()
                ->selectRaw("negocios.*, {$haversine} AS distancia_km", [(float)$lat, (float)$lng, (float)$lat])
                ->having('distancia_km', '<=', $radio)
                ->orderBy('distancia_km');
        } else {
            $query = Negocio::with(['categoria', 'horarios'])
                ->publicos()
                ->orderByRaw("FIELD(plan, 'premium', 'basico', 'gratis')")
                ->orderBy('nombre');
        }

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
