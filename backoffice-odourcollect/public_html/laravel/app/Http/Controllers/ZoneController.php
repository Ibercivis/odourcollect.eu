<?php

namespace App\Http\Controllers;

use App\PointOfInterestZone;
use App\UserZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator, Input, Redirect;

use App\Zone;
use App\User;
use App\Point;
use App\Classes\ZonesClass;

use App\Services\ZoneSlug;
use Auth;

class ZoneController extends Controller
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
     * Show the list of private maps
     *
     * @return Zone
     */
    public function index()
    {
        if(Auth::guard('web')->check()){

             $ZonesClass = new ZonesClass();
             $array_zones = $ZonesClass->ArrayZones();

             $zones = DB::table('zones')->where('deleted_at', NULL)->whereIn('id', $array_zones)->where('active', '1')->get();

             $zones_no_active = DB::table('zones')->where('deleted_at', NULL)->where('active', '0')->get();

        } else {
            $zones = DB::table('zones')->where('deleted_at', NULL)->where('active', '1')->get();

            $zones_no_active = DB::table('zones')->where('deleted_at', NULL)->where('active', '0')->get();
        }


        return view('zones.list', compact('zones', 'zones_no_active'));
    }


    /**
     * Show the form to create a new private map
     */
    public function create()
    {
        return view('zones.create');
    }


    /**
     * Deletea private zone and his relation with users
     */
    public function delete($id)
    {
        $zone = Zone::where('id', $id)->delete();
        $users = UserZone::where('id_zone', $id)->delete();

        
        if(Auth::guard('web')->check()){
            return redirect()->route('zone.list')->withErrors(['success']);
        } else {
            return redirect()->route('admin.zone.list')->withErrors(['success']);
        }
    }

    /**
     * Active private zone and his relation with users
     */
    public function active($id)
    {
        Zone::where('id',$id)->update(
        array(
            'active' => '1'
        ));
        return redirect()->route('admin.zone.list')->withErrors(['success']);
    }


    /**
     * Show the information from a private map
     *
     * @return array
     */
    public function show($id)
    {
         if(Auth::guard('web')->check()){

            $ZonesClass = new ZonesClass();
            $array_zones = $ZonesClass->ArrayZones();

            //$users = DB::table('users')->join('user_zones', 'user_zones.id_user', '=', 'users.id')->whereIn('user_zones.id_zone', $array_zones)->groupBy('user_zones.id_user')->get();
             $users = DB::table('users')->get();

        } else {
            $users = DB::table('users')->get();
        }
        
        $zone = DB::table('zones')->where('id', $id)->first();

        foreach ($users as $user){
            $user->belong = false;
            $belong_zone = DB::table('user_zones')->where('id_user', $user->id)->where('id_zone', $id)->orderBy('id', 'desc')->first();

            if ($belong_zone){
                if ($belong_zone->deleted_at == NULL){$user->belong = true;}
            }
        }

        return view('zones.show' , ['users' => $users, 'zone' => $zone, 'success' => false]);
    }


    /**
     * Add user to a private map
     */
    public function addUser($id, $user)
    {
        $user_zone = new UserZone();
        $user_zone->id_zone = $id;
        $user_zone->id_user = $user;
        $user_zone->save();

        return redirect()->back()->withInput()->withErrors(['success']);
    }


    /**
     * Delete a user from a private map
     */
    public function removeUser($id, $user)
    {
        $user_zone = UserZone::where('id_user', $user)->where('id_zone', $id)->first();

        $ZonesClass = new ZonesClass();
        $array_zones = $ZonesClass->ArrayZones(); 

        if(empty($array_zone)){
            $user = User::where('id',$user)->first();
            $user->type = "User";
            $user->save();
        }
        if ($user_zone){$user_zone->delete();}

        return redirect()->back()->withInput()->withErrors(['success']);
    }

    /**
     * Save the new private map information from form
     */
    public function store(Request $request)
    {
    	$access_token = env('MAPBOX_API_KEY','');

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:190',
            'centroid' => 'required',
            'polygon' => 'required'
        ]);

        $slug_creator = new ZoneSlug();
        $slug = $slug_creator->createSlug($request->get('name'));

        if ($validator->passes()) {

        	$zone = New Zone($request->all());
            $zone->slug = $slug;
            
            /*if(Auth::guard('web')->check()){
            $zone->active = '0';
            } else {
             $zone->active = '1';
             //$zone->id_user = Auth::id();
            }*/
            $zone->active = '0';

        	$centroid = json_decode($request->get('centroid'));
	    	$polygon = json_decode($request->get('polygon'));

	        $longitude = $centroid->geometry->coordinates[0];
	        $latitude = $centroid->geometry->coordinates[1];

	        $zone->longitude = $longitude;
	        $zone->latitude = $latitude;
	        
	        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/'. $longitude . ',' . $latitude . '.json?access_token=' . $access_token;
	        $centroid_info = json_decode(file_get_contents($url), true);

	        foreach ($centroid_info['features'] as $key => $feature) {
	        	$aux = explode(".", $feature['id']);
	        	switch ($aux[0]) {
	        		case 'address':
	        			$zone->address = $feature['place_name'];
	        			$zone->address__mapbox_id = $aux[1];
	        			break;

	        		case 'postcode':
	        			$zone->postal_code = $feature['text'];
	        			$zone->postal_code__mapbox_id = $aux[1];
	        			break;

	        		case 'region':
	        			$zone->region = $feature['text'];
	        			$zone->region__mapbox_id = $aux[1];
	        			break;

	        		case 'place':
	        			$zone->place = $feature['text'];
	        			$zone->place__mapbox_id = $aux[1];
	        			break;

	        		case 'country':
	        			$zone->country = $feature['text'];
	        			$zone->country__mapbox_id = $aux[1];
	        			break;
	        	}
	        }

            $zone->save();

            foreach ($polygon->features[0]->geometry->coordinates[0] as $key => $zone_point) {
            	$point = new Point();
            	$point->id_zone = $zone->id;
            	$point->longitude = $zone_point[0];
	        	$point->latitude = $zone_point[1];
	        	$point->save();
            }

            return redirect()->route('zone.create')->withSuccess('Zone created succesfully!');
        }

        return redirect()->back()->withInput()->withErrors($validator);
    }


    /**
     * Return the points from private map
     *
     * @return Zone
     */
    public function points($id){

        if ($id == 0){
            $zones = Zone::get();
        } else {
            $zones = Zone::where('id', $id)->get();
        }

        foreach ($zones as $zone){
            $points = Point::where('id_zone', $zone->id)->get();
            $zone->points = $points;
        }

        return $zones;
    }

    public function zoneInterestPoint($id, $point){

        if ($id == 0){
            $zones = Zone::get();
        } else {
            $zones = Zone::where('id', $id)->get();
        }

        foreach ($zones as $zone) {
            $points = Point::where('id_zone', $zone->id)->get();
            $zone->points = $points;

            $interest = PointOfInterestZone::where('id_zone', $zone->id)->where('id_point_of_interest', $point)->first();
            if ($interest) {
                $zone->belong = 1;
            } else {
                $zone->belong = 0;
            }
        }

        return $zones;
    }
}
