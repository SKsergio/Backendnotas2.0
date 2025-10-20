<?php

namespace App\Http\Controllers\catalogue;

use App\Models\catalogues\subjects;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Subjects::query();

        //filtros de search en caso que vengan
        if ($request->has('search')) {
            $search = $request->get('search');//obtener el valor a buscar
            $query->where(function ($q) use ($search){
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $Subjects = $query->get();


        if ($Subjects->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($Subjects, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:12',
            'code' => 'required|string|max:12',
            'description' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos ',
                'error' => $validator->errors(),
                'status' => 401,
            ];
            return response()->json($data, 401);
        }

        $subjects = Subjects::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description
        ]);

        return response()->json($subjects, 201);
        
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
        $subjects = Subjects::find($id);

        if(!$subjects){
            return response()->json([
                'message' => 'No hay materia para mostrar con este id'
            ], 404);
        }

        return response()->json($subjects, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $subjects = Subjects::find($id);

        if(!$subjects){
            return response()->json([
                'message' => 'No hay materia con este id'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:12',
            'code' => 'required|string|max:12',
            'description' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $subjects->name = $request->name;
        $subjects->code = $request->code;
        $subjects->description = $request->description;

        $subjects->save();

        $data = [
            'message' => 'Materia actualizada con exito',
            'materia' => $subjects,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, subjects $subjects)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subjects = Subjects::find($id);

        if($subjects->isEmpty()){
            return response()->json([
                'message' => 'No hay materia para eliminar con este id',
            ], 404);
        }

        $subjects->delete();

        return response()->noContent();
    }

        public function restore($id)
    {
        $subjects = Subjects::withTrashed()->find($id);

        if (!$subjects) {
            return response()->json([
                'message' => 'No hay materia para eliminar con este id'
            ], 404);
        }

        $subjects->restore();

       return response()->json($subjects, 200);
    }
}
