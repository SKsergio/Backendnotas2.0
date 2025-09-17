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
    public function index()
    {
        //
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

        if($validator->fails()){

            $data = [
            'message' => 'Error en la validacion',
            'errors' => $validator->errors(),
            'status' => 400
            ];

            return response()->json($data, 400);
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Periods $periods)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periods $periods)
    {
        //
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
    public function destroy(Periods $periods)
    {
        //
    }
}
