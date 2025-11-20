<?php

namespace App\Http\Controllers\Students;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Students\StudentsManagers;
use Illuminate\Support\Carbon;

class StudentManagerController extends Controller
{

    public function index(Request $request)
    {
        $query = StudentsManagers::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('second_name', 'like', "%{$search}%")
                    ->orWhere('first_last_name', 'like', "%{$search}%")
                    ->orWhere('second_last_name', 'like', "%{$search}%")
                    ->orWhere('DUI', 'like', "%{$search}%")
                    ->orWhere('passport', 'like', "%{$search}%")
                ;
            });
        }

        if ($request->input('from_date') && $request->input('until_date')) {
            $from_date = $request->input('from_date');
            $until_date = $request->input('until_date');
            $query->whereBetween('created_at', [$from_date, $until_date]);
        }

        $StudentsManagers = $query->get();

        if ($StudentsManagers->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($StudentsManagers, 200);
    }

    public function store(Request $request)
    {

        // dd('holaaa');
        $validacion = Validator::make($request->all(), [
            'first_name' => 'required|string|max:20',
            'seccond_name' => 'required|string|max:20',
            'first_last_name' => 'required|string|max:20',
            'second_last_name' => 'required|string|max:20',
            'DUI' => 'nullable|string',
            'passport' => 'nullable|string',
            'direction' => 'required|string',
            'birthdate' => 'nullable|date|date_format:Y-m-d',
            'married_surname' => 'nullable|string',
            'email' => 'required|email'
        ]);


        if ($validacion->fails()) {
            return response()->json([
                'message' => 'Error en la validacion de datos',
                'errors'  => $validacion->errors(),
                'status'  => 422
            ], 422);
        }

        try {
            //aca ira el helper para calcular la edad()
            $age = $request->birthdate ? Carbon::parse($request->birthdate)->age : null;
            $newStudentManager =  StudentsManagers::create([
                'DUI' => $request->DUI,
                'passport' => $request->passport,
                'first_name' => $request->first_name,
                'seccond_name' => $request->seccond_name,
                'first_last_name' => $request->first_last_name,
                'second_last_name' => $request->second_last_name,
                'married_surname' => $request->married_surname,
                'direction' => $request->direction,
                'birthdate' => $request->birthdate,
                'email' => $request->email,
                'age' => $age
            ]);

            return response()->json($newStudentManager, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $StudentManager = StudentsManagers::find($id);

        if (!$StudentManager) {
            return response()->json([
                'message' => 'No hay Encargado para mostrar con este id'
            ], 404);
        }

        return response()->json($StudentManager, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function partialUpdate(Request $request, string $id)
    {
        $StudentManager =  StudentsManagers::find($id);

        if (!$StudentManager) {
            return response()->json([
                'message' => 'No hay Encargado para mostrar con este id'
            ], 404);
        }

        $validacion = Validator::make($request->all(), [
            'DUI' => 'nullable|string',
            'first_name' => 'nullable|string|max:20',
            'second_name' => 'nullable|string|max:20',
            'first_last_name' => 'nullable|string|max:20',
            'second_last_name' => 'nullable|string|max:20',
            'married_surname' => 'nullable|string',
            'passport' => 'nullable|string',
            'direction' => 'nullable|string',
            'birthdate' => 'nullable|date|date_format:Y-m-d',
            'email' => 'nullable|email',

        ]);


         if ($validacion->fails()) {
            return response()->json([
                'message' => 'Error en la validacion de datos',
                'errors'  => $validacion->errors(),
                'status'  => 422
            ], 422);
        }


        try {
            //validar que la fecha de nacimiento sea la misma, sino hacer el calculo de nuevo
            if ($request->has('birthdate')) {
                $StudentManager->age = Carbon::parse($request->birthdate)->age;
            }

            $StudentManager->fill($request->only([
                'DUI',
                'first_name',
                'second_name',
                'first_last_name',
                'second_last_name',
                'married_surname',
                'passport',
                'direction',
                'birthdate',
                'email',
            ]));

            $StudentManager->save();

            return response()->json($StudentManager, 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $StudentManager = StudentsManagers::find($id);

        if (!$StudentManager) {
            return response()->json([
                'message' => 'No hay Encargado para mostrar con este id'
            ], 404);
        }

        try {
            $StudentManager->delete();

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
        $StudentManager = StudentsManagers::withTrashed()->find($id);

        if (!$StudentManager) {
            return response()->json([
                'message' => 'No hay Encargado para restablecer con este id'
            ], 404);
        }

        $StudentManager->restore();

        return response()->json($StudentManager, 200);
    }
}
