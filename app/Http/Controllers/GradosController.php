<?php

namespace App\Http\Controllers;

use App\Models\Grados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'name' => 'required|string|max:10',
            'code' => 'required|string|max:10'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'error en la validacion de los datos',
                'error' => $validacion->errors(),
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $name = $request->name;
        $code = $request->code;

        try {
            $newDegree = Grados::create([
                'name' => $name,
                'code' => $code
            ]);

            $data = [
                'degree' => $newDegree,
                'status' => 201
            ];

            return response()->json($data, 201);
        } catch (\Exception $e) {
            $data = [
                'message' => 'ocurrio un error maestro',
                'message' => $e->getMessage(),
                'status' => 500
            ];

            return response()->json($data, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Grados $grados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grados $grados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grados $grados)
    {
        //
    }
}
