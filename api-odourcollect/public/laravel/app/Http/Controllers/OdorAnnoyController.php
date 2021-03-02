<?php

namespace App\Http\Controllers;

use App\OdorAnnoy;
use Illuminate\Http\Request;

class OdorAnnoyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $odor_annoys = OdorAnnoy::get();

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message' => 'Succesfull request.',
                'content' => $odor_annoys,
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
     * @param  \App\OdorAnnoy  $odorAnnoy
     * @return \Illuminate\Http\Response
     */
    public function show(OdorAnnoy $odorAnnoy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OdorAnnoy  $odorAnnoy
     * @return \Illuminate\Http\Response
     */
    public function edit(OdorAnnoy $odorAnnoy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OdorAnnoy  $odorAnnoy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OdorAnnoy $odorAnnoy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OdorAnnoy  $odorAnnoy
     * @return \Illuminate\Http\Response
     */
    public function destroy(OdorAnnoy $odorAnnoy)
    {
        //
    }
}
