<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NegocioCapturaController extends Controller
{
    // GET /api/captura/negocios
    public function index(Request $request)
    {
        $usuario = $request->get('_usuario');

        $query = Negocio::with(['categoria', 'capturista'])
            ->orderBy('created_at', 'desc');

        if ($usuario->esCapturista()) {
            $query->where('capturado_por', $usuario->id);
        }

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }

        $negocios = $query->paginate(20);

        return response()->json($negocios);
    }

    // POST /api/captura/negocios
    public function store(Request $request)
    {
        $usuario = $request->get('_usuario');

        $request->validate([
            'nombre'             => 'required|string|max:200',
            'categoria_id'       => 'required|exists:categorias,id',
            'telefono'           => 'nullable|string|max:20',
            'whatsapp'           => 'nullable|string|max:20',
            'facebook'           => 'nullable|string|max:255',
            'instagram'          => 'nullable|string|max:100',
            'direccion'          => 'nullable|string',
            'colonia'            => 'nullable|string|max:100',
            'referencia'         => 'nullable|string|max:255',
            'lat'                => 'nullable|numeric',
            'lng'                => 'nullable|numeric',
            'descripcion'        => 'nullable|string',
            'servicio_domicilio' => 'boolean',
            'horarios'           => 'nullable|array',
            'horarios.*.dia'           => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'horarios.*.hora_apertura' => 'nullable|date_format:H:i',
            'horarios.*.hora_cierre'   => 'nullable|date_format:H:i',
            'horarios.*.cerrado'       => 'boolean',
        ]);

        $negocio = Negocio::create([
            ...$request->except(['horarios', '_usuario']),
            'estado'        => 'pendiente',
            'capturado_por' => $usuario->id,
        ]);

        // Guardar horarios si vienen
        if ($request->has('horarios')) {
            foreach ($request->horarios as $h) {
                Horario::create([
                    'negocio_id'    => $negocio->id,
                    'dia'           => $h['dia'],
                    'hora_apertura' => $h['hora_apertura'] ?? null,
                    'hora_cierre'   => $h['hora_cierre'] ?? null,
                    'cerrado'       => $h['cerrado'] ?? false,
                ]);
            }
        }

        return response()->json($negocio->load(['categoria', 'horarios']), 201);
    }

    // PUT /api/captura/negocios/{id}
    public function update(Request $request, $id)
    {
        $usuario = $request->get('_usuario');

        $negocio = Negocio::findOrFail($id);

        // Capturista solo puede editar los suyos y si están pendientes/rechazados
        if ($usuario->esCapturista()) {
            if ($negocio->capturado_por !== $usuario->id) {
                return response()->json(['message' => 'No autorizado'], 403);
            }
            if ($negocio->estado === 'aprobado') {
                return response()->json(['message' => 'No se puede editar un negocio aprobado'], 422);
            }
        }

        $request->validate([
            'nombre'             => 'sometimes|required|string|max:200',
            'categoria_id'       => 'sometimes|required|exists:categorias,id',
            'telefono'           => 'nullable|string|max:20',
            'whatsapp'           => 'nullable|string|max:20',
            'facebook'           => 'nullable|string|max:255',
            'instagram'          => 'nullable|string|max:100',
            'direccion'          => 'nullable|string',
            'colonia'            => 'nullable|string|max:100',
            'referencia'         => 'nullable|string|max:255',
            'lat'                => 'nullable|numeric',
            'lng'                => 'nullable|numeric',
            'descripcion'        => 'nullable|string',
            'servicio_domicilio' => 'boolean',
            'horarios'           => 'nullable|array',
        ]);

        $negocio->update($request->except(['horarios', '_usuario']));

        // Reemplazar horarios si vienen
        if ($request->has('horarios')) {
            $negocio->horarios()->delete();
            foreach ($request->horarios as $h) {
                Horario::create([
                    'negocio_id'    => $negocio->id,
                    'dia'           => $h['dia'],
                    'hora_apertura' => $h['hora_apertura'] ?? null,
                    'hora_cierre'   => $h['hora_cierre'] ?? null,
                    'cerrado'       => $h['cerrado'] ?? false,
                ]);
            }
        }

        return response()->json($negocio->load(['categoria', 'horarios']));
    }

    // POST /api/captura/negocios/{id}/foto
    public function subirFoto(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);

        $negocio = Negocio::findOrFail($id);

        $path = $request->file('foto')->store("negocios/{$id}", 'public');

        $negocio->update(['foto' => $path]);

        return response()->json(['foto' => $path]);
    }

    // POST /api/admin/negocios/{id}/aprobar
    public function aprobar(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        $negocio->update(['estado' => 'aprobado']);

        return response()->json(['message' => 'Negocio aprobado', 'negocio' => $negocio]);
    }

    // POST /api/admin/negocios/{id}/rechazar
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'nullable|string|max:255',
        ]);

        $negocio = Negocio::findOrFail($id);
        $negocio->update([
            'estado'          => 'rechazado',
            'motivo_rechazo'  => $request->get('motivo'),
        ]);

        return response()->json(['message' => 'Negocio rechazado', 'negocio' => $negocio]);
    }

    // GET /api/admin/stats
    public function stats(Request $request)
    {
        $stats = Negocio::selectRaw('
                capturado_por,
                COUNT(*) as total,
                SUM(CASE WHEN estado = "aprobado" THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN estado = "pendiente" THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = "rechazado" THEN 1 ELSE 0 END) as rechazados
            ')
            ->with('capturista:id,nombre')
            ->groupBy('capturado_por')
            ->get();

        return response()->json($stats);
    }
}
