<?php 

namespace App\Classes;
use strtotime;
use Auth;
use App\UserZone;
use DB;

class ZonesClass {
 
   /* Función */
function ArrayZones(){

    $user_id = Auth::user()->id;

    //$zone = UserZone::where('id_user', $user_id)->get();

    $zone = DB::table('user_zones')->where('id_user',  $user_id)->where('deleted_at', NULL)->where('admin', '1')->get();
          
    

    $array_zones = array();
    $i = 0;
              
    foreach ($zone as $key => $value) {
        $array_zones[$i] = $value->id_zone;

          $i++;
    }

    	return $array_zones;
    }
} ?>