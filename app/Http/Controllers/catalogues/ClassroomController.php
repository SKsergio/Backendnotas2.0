<?php

namespace App\Http\Controllers\catalogues;

use App\Http\Controllers\Controller;
use App\Models\catalogues\Classrooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classrooms = Classrooms::all();

        if ($classrooms->isEmpty()) {
            return response()->json([
                'message' => 'No hay aulas para mostrar'
            ], 401);
        }

        return response()->json($classrooms, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'name' => 'required|string|max:12',
            'code' => 'required|string|max:12|unique:classrooms',
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
            $newClassroom = Classrooms::create([
                'name' => $name,
                'code' => $code
            ]);

            return response()->json($newClassroom, 201);
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
     public function show(string $id)
    {
        $classroom = Classrooms::find($id);

        if (!$classroom) {
            return response()->json([
                'message' => 'No hay aula para mostrar con este id'
            ], 404);
        }

        return response()->json($classroom, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function partialUpdate(Request $request, $id)
    {
        $classroom = Classrooms::find($id);

        if (!$classroom) {
            return response()->json([
                'message' => 'No hay aulas para mostrar con este id'
            ], 404);
        }
      
        $validacion = Validator::make($request->all(), [
            'name' => 'string|max:12',
            'code' => 'string|max:12|unique:classrooms,code,' . $id . ',id',
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
                $classroom->name = $name;
            }
            if ($request->has('code')) {
                $classroom->code = $code;
            }

            $classroom->save();

            return response()->json($classroom, 201);
        } catch (\Exception $e) {
    
            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     public function destroy($id)
    {
        $classroom = Classrooms::find($id);

        if (!$classroom) {
            return response()->json([
                'message' => 'No hay aulas para eliminar con este id'
            ], 404);
        }

        try {
            $classroom->delete();

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
        $classroom = Classrooms::withTrashed()->find($id);

        if (!$classroom) {
            return response()->json([
                'message' => 'No hay aulas para eliminar con este id'
            ], 404);
        }

        $classroom->restore();

        return response()->json($classroom, 200);
    }
}
