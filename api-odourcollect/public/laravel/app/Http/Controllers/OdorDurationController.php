<?php

namespace App\Http\Controllers;

use App\OdorDuration;
use Illuminate\Http\Request;

class OdorDurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $odor_durations = OdorDuration::get();

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message' => 'Succesfull request.',
                'content' => $odor_durations,
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
     * @param  \App\OdorDuration  $odorDuration
     * @return \Illuminate\Http\Response
     */
    public function show(OdorDuration $odorDuration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OdorDuration  $odorDuration
     * @return \Illuminate\Http\Response
     */
    public function edit(OdorDuration $odorDuration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OdorDuration  $odorDuration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OdorDuration $odorDuration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OdorDuration  $odorDuration
     * @return \Illuminate\Http\Response
     */
    public function destroy(OdorDuration $odorDuration)
    {
        //
    }
}
