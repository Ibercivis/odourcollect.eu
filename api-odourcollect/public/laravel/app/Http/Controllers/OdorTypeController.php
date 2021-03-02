<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\OdorParentType;
use App\OdorType;

class OdorTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $odor_types = OdorParentType::with('childs')->get();

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message' => 'Succesfull request.',
                'content' => $odor_types,
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
     * @param  \App\OdorType  $odorType
     * @return \Illuminate\Http\Response
     */
    public function show(OdorType $odorType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OdorType  $odorType
     * @return \Illuminate\Http\Response
     */
    public function edit(OdorType $odorType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OdorType  $odorType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OdorType $odorType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OdorType  $odorType
     * @return \Illuminate\Http\Response
     */
    public function destroy(OdorType $odorType)
    {
        //
    }
}
