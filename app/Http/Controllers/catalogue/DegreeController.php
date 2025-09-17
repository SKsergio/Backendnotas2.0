<?php

namespace App\Http\Controllers\catalogue;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\catalogues\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    //funcion para traer todos los grados
    public function index()
    {
        $degrees = Degree::all();

        if ($degrees->isEmpty()) {
            return response()->json([
                'message' => 'No hay grados para mostrar'
            ], 200);
        }

        return response()->json($degrees, 200);
    }

    //funcion para almencenar nuevo grado
    public function store(Request $request)
    {

        $validacion = Validator::make($request->all(), [
            'name' => 'required|string|max:12',
            'code' => 'required|string|max:12|unique:degrees',
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
            $newDegree = Degree::create([
                'name' => $name,
                'code' => $code
            ]);

            return response()->json($newDegree, 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    //funcion para traer un solo greado
    public function show($id)
    {
        $degree = Degree::find($id);

        if (!$degree) {
            return response()->json([
                'message' => 'No hay grado para mostrar con este id'
            ], 404);
        }

        return response()->json($degree, 200);
    }

    //funcion para actualizar
    public function partialUpdate(Request $request, $id)
    {
        $degree = Degree::find($id);

        if (!$degree) {
            return response()->json([
                'message' => 'No hay grado para mostrar con este id'
            ], 404);
        }
      
        $validacion = Validator::make($request->all(), [
            'name' => 'string|max:12',
            'code' => 'string|max:12|unique:degrees,code,' . $id . ',id',
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
                $degree->name = $name;
            }
            if ($request->has('code')) {
                $degree->code = $code;
            }

            $degree->save();

            return response()->json($degree, 201);
        } catch (\Exception $e) {
    
            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //eliminar un grado
    public function destroy($id)
    {
        $degree = Degree::find($id);

        if (!$degree) {
            return response()->json([
                'message' => 'No hay grado para eliminar con este id'
            ], 404);
        }

        try {
            $degree->delete();

            return response()->noContent(); 
        } catch (\Exception $e) {
            $data = [
                'message' => 'ocurrio un error en el servidor',
                'error' => $e->getMessage(),
                'status' => 500
            ];
            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //funcion para devolver un registro
    public function restore($id)
    {
        $degree = Degree::withTrashed()->find($id);

        if (!$degree) {
            return response()->json([
                'message' => 'No hay grado para eliminar con este id'
            ], 404);
        }

        $degree->restore();

       return response()->json($degree, 200);
    }
}
