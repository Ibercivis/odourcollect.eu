<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

use Validator;

use App\Zone;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }

    /**
     * Validate a location request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validation(Request $request)
    {
        $response = [];

        $validator = Validator::make($request->all(), [
            'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'], 
            'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
        ],[
            'latitude.regex' => 'Latitude value appears to be incorrect format.',
            'longitude.regex' => 'Longitude value appears to be incorrect format.'
        ]);

        if ($validator->passes()) {
            $response['result'] = true;
            return $response;
        }

        $aux = $validator->errors();
        $errors = [];
        if ($aux->has('latitude')) { $errors['latitude'] = $aux->get('latitude'); }
        if ($aux->has('longitude')) { $errors['longitude'] = $aux->get('longitude'); }

        $response['result'] = false;
        $response['errors'] = $errors;
        return $response;
    }

    public function getLocationInfo(Request $request)
    {
        $access_token = env('MAPBOX_API_KEY');
        
        $location = new Location();

        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');
        /*                        
        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/'. $longitude . ',' . $latitude . '.json?access_token=' . $access_token;
        $centroid_info = json_decode(file_get_contents($url), true);

        foreach ($centroid_info['features'] as $key => $feature) {
            $aux = explode(".", $feature['id']);
            switch ($aux[0]) {
                case 'address':
                    $location->address = $feature['place_name'];
                    $location->address__mapbox_id = $aux[1];
                    break;

                case 'postcode':
                    $location->postal_code = $feature['text'];
                    $location->postal_code__mapbox_id = $aux[1];
                    break;

                case 'region':
                    $location->region = $feature['text'];
                    $location->region__mapbox_id = $aux[1];
                    break;

                case 'place':
                    $location->place = $feature['text'];
                    $location->place__mapbox_id = $aux[1];
                    break;

                case 'country':
                    $location->country = $feature['text'];
                    $location->country__mapbox_id = $aux[1];
                    break;
            }
        }
        */
        $location->longitude = $longitude;
        $location->latitude = $latitude;

        return $location;
    }

}
