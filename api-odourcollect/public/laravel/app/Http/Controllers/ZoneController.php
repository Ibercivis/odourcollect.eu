<?php

namespace App\Http\Controllers;

use App\Stat;
use Illuminate\Http\Request;

use App\Zone;
use App\Point;
use App\User;
use App\Odor;
use App\PointOfInterest;
use App\Services\PointLocation;

use Illuminate\Support\Facades\DB;
use Validator;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zones = Zone::with('points')->get();

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message' => 'Succesfull request.',
                'content' => $zones,
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @param  Integer  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zone = Zone::with('points')->find($id);
        if($zone){

//            $stat = new Stat();
//            $stat->type = 'Zone';
//            $stat->id_target = $id;
//            $stat->id_user = $user;
//            $stat->save();

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'finded' => true,
                    'message' => "Zone ".$id." has been finded.",
                    'object' => $zone,
                ]
            ], 200);
        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'finded' => false,
                'message' => "Resource not found.",
            ]
        ], 400);

    }


    public function zoneAttachPoints()
    {        

        $pointLocation = new pointLocation();

        $points = PointOfInterest::get();

        foreach ($points as $key => $pointOfInterest) {

            $pointOfInterest_location = $pointOfInterest->latitude.' '.$pointOfInterest->longitude;

            $zones = Zone::with('points')->get();

            foreach ($zones as $key => $zone) {
                $polygon = [];
                foreach ($zone->points as $key => $point) {
                    $aux = '';
                    $aux = $point->latitude. ' ' .$point->longitude;
                    $polygon[] = $aux;
                }

                $result = $pointLocation->pointInPolygon($pointOfInterest_location, $polygon);

                $pointOfInterest->zones()->detach($zone->id);
                $pointOfInterest->zones()->attach($zone->id, ['verified' => 1]);

            }


        }

        
    }
}
