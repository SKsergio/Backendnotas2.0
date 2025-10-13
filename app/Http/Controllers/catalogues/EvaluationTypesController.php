<?php

namespace App\Http\Controllers\catalogues;

use App\Models\catalogues\evaluationTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EvaluationTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evaluationTypes = EvaluationTypes::all();
        
        if($evaluationTypes->isEmpty()){
            return response()->json([
                'message' => 'No hay tipos de evaluaciones para mostrar'
            ], 204);
        }

        return response()->json($evaluationTypes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        if($validator->fails()){

            $data = [
            'message' => 'Error en la validacion de datos',
            'errors' => $validator->errors(),
            'status' => 401
            ];

            return response()->json($data, 401);
        }

        $evaluationTypes = EvaluationTypes::create([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return response()->json($evaluationTypes, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluationTypes = evaluationTypes::find($id);

        if(!$evaluationTypes){
            $data = [
                'message' => 'Tipo de evaluacion no encontrado con este id',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($evaluationTypes, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $evaluationTypes = evaluationTypes::find($id);

        if(!$evaluationTypes){
            $data = [
                'message' => 'No hay tipo de evaluacion para mnostrar con este id',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),
        [
            'name' => 'string|max:12',
            'code' => 'string|max:12|unique:degrees,code,' . $id . ',id',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion para actualizar este tipo de evaluacion',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $evaluationTypes->name = $request->name;
        $evaluationTypes->code = $request->code;

        $evaluationTypes->save();

        $data = [
            'message' => 'Tipo de evaluacion actualizado',
            'TipoEvaluacion' => $evaluationTypes,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, evaluationTypes $evaluationTypes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluationTypes = evaluationTypes::find($id);

        if(!$evaluationTypes){
            $data = [
                'message' => 'Tipo de evaluacion no existe',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $evaluationTypes->delete();

        return response()->noContent();
    }

    public function restore($id)
    {
        $evaluationTypes = evaluationTypes::withTrashed()->find($id);

        if (!$evaluationTypes) {
            return response()->json([
                'message' => 'No hay tipo de evaluacion para eliminar con este id'
            ], 404);
        }

        $evaluationTypes->restore();

       return response()->json($evaluationTypes, 200);
    }
}
