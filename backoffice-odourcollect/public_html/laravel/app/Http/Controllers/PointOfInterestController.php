<?php

namespace App\Http\Controllers;

use App\PointOfInterest;
use App\PointOfInterestType;
use App\PointOfInterestZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Validator, Input, Redirect;
use App\Classes\ZonesClass;

class PointOfInterestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    
    /**
     * Show the list of points of interest
     *
     * @return PointOfInterest
     */
    public function index()
    {
        if(Auth::guard('web')->check()){
            
            $ZonesClass = new ZonesClass();

            $array_zones = $ZonesClass->ArrayZones();

            $points = PointOfInterest::where('points_of_interest.deleted_at', NULL)->join('point_of_interest_zones', 'point_of_interest_zones.id_point_of_interest', '=', 'points_of_interest.id')->whereIn('point_of_interest_zones.id_zone', $array_zones)->get();

            foreach ($points as $point){
                $type = PointOfInterestType::where('id', $point->id_point_of_interest_type)->first();
                $point->odour_type = $type->name;
            }

        } else {

            $points = PointOfInterest::where('deleted_at', NULL)->get();

            foreach ($points as $point){
                $type = PointOfInterestType::where('id', $point->id_point_of_interest_type)->first();
                $point->odour_type = $type->name;
            }

        }
        

        return view('points.list', ['points' => $points]);
    }


    /**
     * Delete a point of interest
     */
    public function delete($id)
    {
        
        $points = PointOfInterest::where('id', $id)->delete();
        return redirect()->route('point.list')->withErrors(['success']);
    }

    /**
     * Return tupes of point of interest to use it on the form
     *
     * @return PointOfInterestType
     */
    public function create()
    {
        $odour_types = PointOfInterestType::where('deleted_at', NULL)->get();
        return view('points.create', ['types' => $odour_types]);
    }


    /**
     * Show the information of a point of interest
     *
     * @return array
     */
    public function show($id)
    {
        $zones = DB::table('zones')->where('deleted_at', NULL)->get();
        $point = PointOfInterest::where('id', $id)->first();

        foreach ($zones as $zone){
            $zone->belong = false;
            $belong_zone = DB::table('point_of_interest_zones')->where('id_zone', $zone->id)->where('id_point_of_interest', $id)->orderBy('id', 'desc')->first();

            if ($belong_zone){
                if ($belong_zone->deleted_at == NULL){$zone->belong = true;}
            }
        }

        return view('points.show' , ['zones' => $zones, 'point' => $point, 'success' => false]);
    }


    /**
     * Add the point of interest to one zone
     */
    public function addZone($id, $zone)
    {
        $point_zone = new PointOfInterestZone();
        $point_zone->id_zone = $zone;
        $point_zone->id_point_of_interest = $id;
        $point_zone->save();

        return redirect()->route('point.show', ['id' => $id])->withErrors(['success']);
    }


    /**
     * Remove the point of interest from one zone
     */
    public function removeZone($id, $zone)
    {
        $point_zone = PointOfInterestZone::where('id_zone', $zone)->where('id_point_of_interest', $id)->first();
        if ($point_zone){$point_zone->delete();}

        return redirect()->route('point.show', ['id' => $id])->withErrors(['success']);
    }


    /**
     * Save the information from the form about the new point of interest
     */
    public function save(Request $request)
    {
        $access_token = env('MAPBOX_API_KEY','');
        $odour_types = DB::table('odor_parent_types')->get();
        $type_rule = array();

        foreach ($odour_types as $odour_type) {
            array_push($type_rule, $odour_type->id);
        }



        $validator = Validator::make($request->all(), [
            'name' => 'required|max:190',
            'centroid' => 'required',
        ]);
        
        $slug = str_slug($request->get('name'));

        if ($validator->passes()) {
            $point = New PointOfInterest($request->all());
            $point->slug = $slug;
            $point->id_point_of_interest_type = $request->get('type');
            $point->id_admin = Auth::user()->id;
            $point->published_at = now();

            $type = PointOfInterestType::where('id', $request->get('type'))->first();

            $point->icon = $type->slug . '-spot.png';
            $centroid = json_decode($request->get('centroid'));

            $longitude = $centroid->lng;
            $latitude = $centroid->lat;

            $point->longitude = $longitude;
            $point->latitude = $latitude;

            $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/'. $longitude . ',' . $latitude . '.json?access_token=' . $access_token;
            $centroid_info = json_decode(file_get_contents($url), true);

            foreach ($centroid_info['features'] as $key => $feature) {
                $aux = explode(".", $feature['id']);
                switch ($aux[0]) {
                    case 'address':
                        $point->address = $feature['place_name'];
                        $point->address__mapbox_id = $aux[1];
                        break;

                    case 'postcode':
                        $point->postal_code = $feature['text'];
                        $point->postal_code__mapbox_id = $aux[1];
                        break;

                    case 'region':
                        $point->region = $feature['text'];
                        $point->region__mapbox_id = $aux[1];
                        break;

                    case 'place':
                        $point->place = $feature['text'];
                        $point->place__mapbox_id = $aux[1];
                        break;

                    case 'country':
                        $point->country = $feature['text'];
                        $point->country__mapbox_id = $aux[1];
                        break;
                }
            }

            $point->save();

            return redirect()->route('zone.create')->withSuccess('Activity of interest created succesfully!');
        }

        return redirect()->back()->withInput()->withErrors($validator);
    }


    /**
     * Show the information about the new type of point of interest
     */
    public function saveType(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:190',
            'file' => 'required'
        ]);

        if ($validator->passes()) {
            $type = new PointOfInterestType();
            $type->name = $request->get('name');
            $type->slug = str_slug($request->get('name'));

            $target_dir =  $_SERVER['DOCUMENT_ROOT'] . '/assets/images/interest/';
            $target_file = $target_dir . $type->slug . '-spot.png';
            $imageFileType = strtolower(pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["file"]["tmp_name"]);

            $size = $check[3];
            $dimensions = explode(' ', $size);
            $width = explode('=', $dimensions[0]);
            $width = substr($width[1], 1, strlen($width[1]) - 2);
            $height = explode('=', $dimensions[1]);
            $height = substr($height[1], 1, strlen($height[1]) - 2);

            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["file"]["tmp_name"]);
                if($check == false) {return redirect()->back()->withErrors(['error']);}
            }

            if ($width != '84' || $height != '84'){return redirect()->back()->withErrors(['error']);}
            if($imageFileType != "png") {return redirect()->back()->withErrors(['error']);}
            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {return redirect()->back()->withErrors(['error']);}

            $type->save();

            return redirect()->route('point.type')->withSuccess('Type of activity of interest created succesfully!');

        }
        return redirect()->back()->withInput()->withErrors($validator);
    }


    /**
     * Remove the point of interest type
     */
    public function deleteType($id)
    {
        $type = PointOfInterestType::where('id', $id)->delete();
        return redirect()->route('point.type', ['id' => $id])->withErrors(['success']);
    }


    /**
     * Show the list of types of points of interest
     *
     * @return PointOfInterestType
     */
    public function showTypes()
    {
        $types = PointOfInterestType::where('deleted_at', NULL)->get();
        return view('points.type' , ['types' => $types]);
    }


    /**
     * Return the points of interest
     *
     * @return PointOfInterest
     */
    public function points($id){

        $points = PointOfInterest::get();

        if ($id == 0){
            $points = PointOfInterest::get();
        } else {
            $points = PointOfInterest::where('id', $id)->get();
        }

        return $points;
    }
}
