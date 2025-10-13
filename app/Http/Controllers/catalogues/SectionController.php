<?php

namespace App\Http\Controllers\catalogues;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\catalogues\Sections;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Sections::query();

        //filtros de search en caso que vengan
        if ($request->has('search')) {
            $search = $request->get('search');//obtener el valor a buscar
            $query->where(function ($q) use ($search){
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $sections = $query->get();


        if ($sections->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($sections, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'name' => 'required|string|max:12',
            'code' => 'required|string|max:12|unique:sections',
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validacion->errors(),
                'status' => 401
            ];
            return response()->json($data, 401);
        }

        $name = $request->name;
        $code = $request->code;

        try {
            $newSection = Sections::create([
                'name' => $name,
                'code' => $code
            ]);

            return response()->json($newSection, 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $section = Sections::find($id);

        if (!$section) {
            return response()->json([
                'message' => 'No hay seccion para mostrar con este id'
            ], 404);
        }

        return response()->json($section, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function partialUpdate(Request $request, $id)
    {
        $section = Sections::find($id);

        if (!$section) {
            return response()->json([
                'message' => 'No hay secciones para mostrar con este id'
            ], 404);
        }

        $validacion = Validator::make($request->all(), [
            'name' => 'string|max:12',
            'code' => 'string|max:12|unique:sections,code,' . $id . ',id',
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validacion->errors(),
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $name = $request->name;
        $code = $request->code;

        try {
            if ($request->has('name')) {
                $section->name = $name;
            }
            if ($request->has('code')) {
                $section->code = $code;
            }

            $section->save();

            return response()->json($section, 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //eliminar una seccion
    public function destroy($id)
    {
        $section = Sections::find($id);

        if (!$section) {
            return response()->json([
                'message' => 'No hay seccion para eliminar con este id'
            ], 404);
        }

        try {
            $section->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //funcion para devolver un registro
    public function restore($id)
    {
        $section = Sections::withTrashed()->find($id);

        if (!$section) {
            return response()->json([
                'message' => 'No hay seccion para eliminar con este id'
            ], 404);
        }

        $section->restore();

        return response()->json($section, 200);
    }
}
