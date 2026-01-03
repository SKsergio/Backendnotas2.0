<?php

namespace App\Http\Controllers\Students;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Students\StudentsManagers;
use Illuminate\Support\Carbon;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentManagerController extends Controller
{

    public function index(Request $request)
    {
        $query = StudentsManagers::query()->with('file');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('seccond_name', 'like', "%{$search}%")
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
            'email' => 'required|email',
            'age' => 'required|integer',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_type_id' => 'nullable|integer'
        ]);


        if ($validacion->fails()) {
            return response()->json([
                'message' => 'Error en la validacion de datos',
                'errors'  => $validacion->errors(),
                'status'  => 422
            ], 422);
        }

        try {
            $age = $request->birthdate ? Carbon::parse($request->birthdate)->age : null;
            if ($age != $request->age) {
                return response()->json([
                    'message' => 'la edad esta incorrecta'
                ], 422);
            }
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
                'age' => $request->age
            ]);

            //almacenar la foto
            $file = $request->file('photo');
            if (!$file) {
                return response()->json([
                    'message' => 'No se envió ningún archivo en el campo "file".'
                ], 400);
            }

            $path = $file->store('uploads/ManagerStudents', 'public');

            $newStudentManager->file()->create([
                'file_type_id' => $request->file_type_id,
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension()
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
        $StudentManager = StudentsManagers::with('file')->find($id);

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

        // Debug: Ver exactamente qué recibe el request
        return response()->json([
            'request_all' => $request->all(),
            'request_input' => $request->input(),
            'request_method' => $request->method(),
            'content_type' => $request->header('content-type'),
            'has_files' => $request->hasFile('photo') ? 'yes' : 'no',
            'files' => $request->files->all(),
        ], 200);
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
