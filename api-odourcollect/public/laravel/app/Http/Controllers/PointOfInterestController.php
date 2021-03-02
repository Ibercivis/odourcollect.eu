<?php

namespace App\Http\Controllers;

use App\PointOfInterestType;
use Illuminate\Http\Request;

use App\PointOfInterest;
use App\OdorType;

class PointOfInterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $points = PointOfInterest::zone($request->get('zone'))->get();

        if( count( $points ) > 0 ){


            foreach ($points as $key => $point) {
                $type = PointOfInterestType::where('id', $point->id_point_of_interest_type)->first();
                if($type){
                    $point->icon = $type->slug . '-spot.png';
                }
            }

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'message' => 'Succesfull request.',
                    'content' => $points,
                ]
            ], 200);
        }

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message' => 'No points found',
                'content' => null,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
