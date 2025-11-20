<?php

namespace App\Http\Controllers\catalogues;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\catalogues\TypeFile;
use Illuminate\Support\Facades\Validator;
class TypeFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TypeFile::query();

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

        $typeFile = $query->get();

        if ($typeFile->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($typeFile, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validacion = Validator::make($request->all(), [
            'name' => 'required|string|max:25',
            'code' => 'required|string|max:16|unique:type_files',
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validacion->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }

        $name = $request->name;
        $code = $request->code;

        try {
            $newFileType = TypeFile::create([
                'name' => $name,
                'code' => $code
            ]);

            return response()->json($newFileType, 201);
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
        $fileType = TypeFile::find($id);

        if (!$fileType) {
            return response()->json([
                'message' => 'No hay Tipo de Archivo para mostrar con este id'
            ], 404);
        }

        return response()->json($fileType, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function partialUpdate(Request $request, string $id)
    {
        $fileType = TypeFile::find($id);

        if (!$fileType) {
            return response()->json([
                'message' => 'No hay Tipo de archivo para eliminar con este id'
            ], 404);
        }

        $validacion = Validator::make($request->all(), [
            'name' => 'string|max:12',
            'code' => 'string|max:12|unique:type_files,code,' . $id . ',id',
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validacion->errors(),
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        try {
            $fileType->fill($request->only([
                'code',
                'name'
            ]));

            $fileType->save();

            return response()->json($fileType, 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error interno',
                'error' => $e->getMessage()
            ], 500);
        }


    }

    public function destroy($id)
    {
        $fileType = TypeFile::find($id);

        if (!$fileType) {
            return response()->json([
                'message' => 'No hay Tipo de archivo para eliminar con este id'
            ], 404);
        }

        try {
            $fileType->delete();

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
        $fileType = TypeFile::withTrashed()->find($id);

        if (!$fileType) {
            return response()->json([
                'message' => 'No hay Tipo de archivo para restablecer con este id'
            ], 404);
        }

        $fileType->restore();

       return response()->json($fileType, 200);
    }
}
