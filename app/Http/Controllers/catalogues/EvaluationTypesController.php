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
    public function index(Request $request)
    {

        $query = EvaluationTypes::query();

        //filtros de search en caso que vengan
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->input('from_date') && $request->input('until_date')) {
            $from_date = $request->input('from_date');
            $until_date = $request->input('until_date');
            $query->whereBetween('created_at', [$from_date, $until_date]);
        }

        $EvaluationTypes = $query->get();


        if ($EvaluationTypes->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($EvaluationTypes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {

            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 422
            ];

            return response()->json($data, 422);
        }

        try {
            $evaluationTypes = EvaluationTypes::create([
                'name' => $request->name,
                'code' => $request->code
            ]);

            return response()->json($evaluationTypes, 201);
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
        $evaluationTypes = evaluationTypes::find($id);

        if (!$evaluationTypes) {
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

        if (!$evaluationTypes) {
            $data = [
                'message' => 'No hay tipo de evaluacion para mnostrar con este id',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'string',
                'code' => 'string|max:12|unique:evaluation_types,code,' . $id . ',id',
            ]
        );

        if ($validator->fails()) {
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

        return response()->json($evaluationTypes, 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluationTypes = evaluationTypes::find($id);

        if (!$evaluationTypes) {
            $data = [
                'message' => 'Tipo de evaluacion no existe',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        try {
            $evaluationTypes->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
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
