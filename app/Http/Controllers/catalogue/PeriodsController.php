<?php

namespace App\Http\Controllers\catalogue;

use App\Models\catalogues\Periods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PeriodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Periods::query();

        //filtros de search en caso que vengan
        if ($request->has('search')) {
            $search = $request->get('search'); //obtener el valor a buscar
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $Periods = $query->get();


        if ($Periods->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($Periods, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'year' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        if ($validator->fails()) {

            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 401
            ];

            return response()->json($data, 401);
        }

        $periods = Periods::create([
            'name' => $request->name,
            'code' => $request->code,
            'dateTimes' => now(),
            'year' => $request->year,
            'from' => $request->from,
            'to' => $request->to
        ]);

        return response()->json($periods, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $periods = Periods::find($id);

        if (!$periods) {
            $data = [
                'message' => 'Periodo no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($periods, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $periods = Periods::find($id);

        if (!$periods) {
            $data = [
                'message' => 'El periodo no se ha encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'string|max:12',
                'code' => 'string|max:12|unique:degrees,code,' . $id . ',id',
                'year' => 'integer',
                'from' => 'date',
                'to' => 'date',
            ]
        );

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion para actualizar este grado',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $periods->name = $request->name;
        $periods->code = $request->code;
        $periods->year = $request->year;
        $periods->from = $request->from;
        $periods->to = $request->to;

        $periods->save();

        $data = [
            'message' => 'Grado actualizado',
            'grado' => $periods,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Periods $periods)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $periods = Periods::find($id);

        if (!$periods) {
            $data = [
                'message' => 'Periodo no existe',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $periods->delete();

        return response()->noContent();
    }

    //funcion para devolver un registro
    public function restore($id)
    {
        $periods = Periods::withTrashed()->find($id);

        if (!$periods) {
            return response()->json([
                'message' => 'No hay periodo para eliminar con este id'
            ], 404);
        }

        $periods->restore();

        return response()->json($periods, 200);
    }
}
