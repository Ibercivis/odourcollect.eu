<?php

namespace App\Http\Controllers;

use App\OdorIntensity;
use Illuminate\Http\Request;

class OdorIntensityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $odor_instensities = OdorIntensity::get();

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message' => 'Succesfull request.',
                'content' => $odor_instensities,
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OdorIntensity  $odorIntensity
     * @return \Illuminate\Http\Response
     */
    public function show(OdorIntensity $odorIntensity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OdorIntensity  $odorIntensity
     * @return \Illuminate\Http\Response
     */
    public function edit(OdorIntensity $odorIntensity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OdorIntensity  $odorIntensity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OdorIntensity $odorIntensity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OdorIntensity  $odorIntensity
     * @return \Illuminate\Http\Response
     */
    public function destroy(OdorIntensity $odorIntensity)
    {
        //
    }
}
