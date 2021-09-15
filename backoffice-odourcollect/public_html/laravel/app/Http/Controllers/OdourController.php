<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Location;
use App\Odor;
use App\OdorAnnoy;
use App\OdorDuration;
use App\OdorIntensity;
use App\OdorParentType;
use App\OdorTrack;
use App\OdorType;
use App\Stat;
use App\User;
use App\Zone;
use App\OdorZone;
use App\UserZone;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Auth;
use Storage;

use App\Classes\ZonesClass;


class OdourController extends Controller
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
     * Show the list of odors according to the filter applied
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*Verify this Zone Admin*/
        if(Auth::guard('web')->check()){
        
        $ZonesClass = new ZonesClass();

        $array_zones = $ZonesClass->ArrayZones();

        $map = $request->get('map');
        $type = $request->get('type');
        $subtype = $request->get('subtype');
        $status = $request->get('status');
        $intensity = $request->get('intensity');
        $annoy = $request->get('annoy');
        $publish_date_src = $request->get('publish_date_src');
        $publish_date_dst = $request->get('publish_date_dst');
        if ($publish_date_src == null) $publish_date_src = '';
        if ($publish_date_dst == null) $publish_date_dst = '';
        $zone = Zone::where('id', $map)->first();
        $postalCodeArray = [];
        $i = 0;

        $statusSelection;
        
        if(empty($status)){$statusSelection='published';}elseif(!empty($status) && $status === '1'){$statusSelection='published';}elseif(!empty($status) && $status === '2'){$statusSelection='deleted'; $status='';}
         elseif(!empty($status) && $status === '0'){$statusSelection='No';}

        foreach ($array_zones as $key => $value) {
           $zone = Zone::where('id', $value)->first();
           
           if(!empty($zone->postal_code)){
            $postalCodeArray[$i] = $zone->postal_code;
            $i++;
           }
           
        }
        if(!empty($subtype)){
            $odours = Odor::zone($map)->subtype($subtype)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')->whereIn('odor_zones.id_zone', $array_zones)->get();
        } else {
            $odours = Odor::zone($map)->type($type)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')->whereIn('odor_zones.id_zone', $array_zones)->get();
        }
        
    
        
        $maps = Zone::whereIn('id', $array_zones)->get();

        
        } else {
            
        $map = $request->get('map');
        $type = $request->get('type');
        $subtype = $request->get('subtype');
        $status = $request->get('status');
        $intensity = $request->get('intensity');
        $annoy = $request->get('annoy');
        $publish_date_src = $request->get('publish_date_src');
        $publish_date_dst = $request->get('publish_date_dst');
        if ($publish_date_src == null) $publish_date_src = '';
        if ($publish_date_dst == null) $publish_date_dst = '';

        $statusSelection;
        
        if(empty($status)){$statusSelection='published';}elseif(!empty($status) && $status === '1'){$statusSelection='published';}elseif(!empty($status) && $status === '2'){$statusSelection='deleted'; $status='';}
         elseif(!empty($status) && $status === '0'){$statusSelection='No';}

        $zone = Zone::where('id', $map)->first();
        if(!empty($subtype)){
            $odours = Odor::zone($map)->subtype($subtype)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->get();
        } else {
            $odours = Odor::zone($map)->type($type)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->get();
        }
        
        
        $maps = Zone::get();

        }

        $types = OdorParentType::get();
       

       
        $intensities = OdorIntensity::get();

        $annoys = OdorAnnoy::get();

        $contador = 0;
        foreach ($odours as $odour){
            $user = User::where('id', $odour->id_user)->first();
            if(empty($odour->id_odor)){
                $odour->id_odor = $odour->id;
            }
            if(!empty($user->username)) {
                $odour->user = $user->username;
            
            } else {
                $odour->user = "";
            }
            
            
            $type = OdorType::where('id', $odour->id_odor_type)->first();
            $typefather = OdorParentType::where('id', $type->id_odor_parent_type)->first();
            $odour->odour_type = $type->name;
            $odour->odour_type_father = $typefather->name;

            /* $address = Location::where('id_odor', $odour->id)->first();
            if (isset($address->address)){
                $odour->address = $address->address;
            } else{
                $odour->address = "";
            }
            echo "<pre>";
            print_r($odour);
            echo "</pre>";

            die(); */
        
            # $userTimezone = new DateTimeZone('Europe/Berlin');
            $gmtTimezone = new DateTimeZone('UTC');
            $myDateTime = new DateTime($odour->published_at, $gmtTimezone);
            # $offset = $userTimezone->getOffset($myDateTime);
            # $myInterval=DateInterval::createFromDateString((string)0 . 'seconds');
            # $myDateTime->add($myInterval);
            $result = $myDateTime->format('Y-m-d H:i:s');
            $odour->published_at = $result;

            if($statusSelection === $odour->status || ($status === '0' && $statusSelection === $odor->verified)){
            }else{
                unset($odours[$contador]);
            }
            $contador+=1;
        }
        if(!empty($_GET['type'])){
            $subtypes = DB::table('odor_types')->where('id_odor_parent_type', $_GET['type'])->get();
        } else {
            $subtypes = DB::table('odor_types')->get();
        }
        
       
       
        return view('odours.list', ['odours' => $odours, 'maps' => $maps, 'types' => $types, 'subtypes' => $subtypes, 'intensities' => $intensities, 'annoys' => $annoys]);
    }


    /**
     * Returns the coordinates of an odor or a list of them
     *
     * @return Odor
     */
    public function getMarkers($id)
    {
        $odours = Odor::where('id', $id)->get();

        foreach ($odours as $odour){
            $user = User::where('id', $odour->id_user)->first();
            //$odour->user = $user->name . ' ' . $user->surname;

            $type = DB::table('odor_types')->where('id', $odour->id_odor_type)->first();
            $odour->odour_type = $type->name;

            $address = DB::table('locations')->where('id_odor', $odour->id)->first();
            $odour->lat = $address->latitude;
            $odour->lng = $address->longitude;
        }

        return $odours;
    }


    /**
     * Returns the coordinates of odors from private zone
     *
     * @return Odor
     */
    public function getZoneMarkers($id)
    {
        if ($id == 0){
            $odours = Odor::get();
        } else {
            $odours = Odor::zone($id)->get();
        }
        foreach ($odours as $key => $odour) {
            $user = User::where('id', $odour->id_user)->first();
            $odours[$key]->user = $user->name . ' ' . $user->surname;

            $type = DB::table('odor_types')->where('id', $odour->id_odor_type)->first();
            $odours[$key]->odour_type = $type->name;

            $address = DB::table('locations')->where('id_odor', $odour->id)->get();
            
            foreach ($address as $keys => $value) {
                 $odours[$key]->lat = $value->latitude;
                 $odours[$key]->lng = $value->longitude;
            }
           
        }
        
        
        return $odours;
    }


    /**
     * Returns the information of unverified odors
     *
     * @return Odor
     */
    public function getUnverifiedMarkers()
    {
        if(Auth::guard('web')->check()){
            
            $ZonesClass = new ZonesClass();

            $array_zones = $ZonesClass->ArrayZones();

            $odours = DB::table('odors')
               ->select('odors.*', 'odor_zones.id_zone')
               ->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')
               ->whereIn('odor_zones.id_zone', $array_zones)
               ->where('odors.verified', 0)
               ->orderBy('published_at', 'desc')->get();
        
        } else {
            $odours = Odor::where('verified', 0)->get();

        }

        foreach ($odours as $odour){
            $user = User::where('id', $odour->id_user)->first();
            $odour->user = $user->name . ' ' . $user->surname;

            $type = DB::table('odor_types')->where('id', $odour->id_odor_type)->first();
            $odour->odour_type = $type->name;

            $address = DB::table('locations')->where('id_odor', $odour->id)->first();

            //$address = DB::table('locations')
                //->join('odor_zones', 'odor_zones.id_odor', '=', 'locations.id_odor')
                //->where('odor_zones.id_zone', '=', '2')
                //->first();
            $odour->lat = $address->latitude;
            $odour->lng = $address->longitude;
        }
        return $odours;
    }


    /**
     * Verifies an odor
     */
    public function verify($id)
    {
        $odour = Odor::where('id', $id)->first();
        $odour->verified = 1;
        $odour->save();

        return redirect()->back()->withInput()->withErrors(['success']);
    }


    /**
     * Returns all the information of an odor
     *
     * @return Odor
     */
    public function show($id)
    {
        $odour = Odor::where('id', $id)->first();
    
        $subtype = OdorType::where('id', $odour->id_odor_type)->first();
        $odour->subtype = $subtype->name;

        $type = OdorParentType::where('id', $subtype->id_odor_parent_type)->first();
        $odour->type = $type->name;

        # $userTimezone = new DateTimeZone('Europe/Berlin');
        $gmtTimezone = new DateTimeZone('UTC');
        $myDateTime = new DateTime($odour->published_at, $gmtTimezone);
        # $offset = $userTimezone->getOffset($myDateTime);
        # $myInterval=DateInterval::createFromDateString((string)7200 . 'seconds');
        # $myDateTime->add($myInterval);
        $result = $myDateTime->format('Y-m-d H:i:s');
        $odour->published_at = $result;
        $odour->published_at = date('F j, Y, g:i a', strtotime($odour->published_at));

        $odour->count_comments = sizeof(Comment::where('id_odor', $id)->get());

        $location = DB::table('locations')->where('id_odor', $id)->first();
        $confirmations = DB::table('likes')->where('id_odor', $id)->get();

        $odour->location = $location;
        $odour->confirmations = $confirmations;

        $user = User::where('id', $odour->id_user)->first();
        $odour->user = $user;

        $stats = Stat::where('type', 'Odor')->where('id_target', $id)->get();
        $odour->stats = sizeof($stats);

        $intensity = OdorIntensity::where('id', $odour->id_odor_intensity)->first();
        $odour->intensity = $intensity->name;
        $odour->index_intensity = $intensity->power;

        $duration = OdorDuration::where('id', $odour->id_odor_duration)->first();
        if(!empty($duration->name)){
        $odour->duration = $duration->name;
        } else {
        $odour->duration = "";
        }

        $annoy = OdorAnnoy::where('id', $odour->id_odor_annoy)->first();
        $odour->annoy = $annoy->name;
        $odour->index_annoy = $annoy->index;

        return view('odours.show', ['odour' => $odour]);
    }
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
    public function importCsv(Request $request)
    {
     //$extension = $request->file('csv')->getClientOriginalExtension();
     
     $file = $request->file('csv');

        
     $customerArr = $this->csvToArray($file);

        foreach ($customerArr as $key => $value) {
           //echo "<pre>";
           if(!empty($value['id_odor_type'])){
           
            //echo "</pre>";
            
            $odor = new Odor();
            $odor->id_odor_type = $value['id_odor_type'];
            $odor->id_user = $value['id_user'];
            $odor->other = $value['other'];
            $odor->verified = 1;
            $odor->track = $value['track'];
            $odor->status = $value['status'];
            $odor->name = $value['name'];
            $odor->description = $value['description'];
            $odor->origin = $value['origin'];
            $odor->color = $value['color'];
            $odor->id_odor_intensity = $value['id_odor_intensity'];
            $odor->id_odor_duration = $value['id_odor_duration'];
            $odor->id_odor_annoy = $value['id_odor_annoy'];
            $odor->published_at = $value['published_at'];
            $odor->save();

           
           $odorzone = new OdorZone();
            $odorzone->id_zone = $value['id_zone'];
            $odorzone->id_odor = $odor->id;
            $odorzone->save();

            $location = new Location();
            $location->address = $value['address'];
            $location->latitude = $value['latitude'];
            $location->longitude = $value['longitude'];
            $location->postal_code = $value['postal_code'];
            $location->place = $value['place'];
            $location->region = $value['region'];
            $location->country = $value['country'];
            $location->id_odor = $odor->id;
            $location->save();
            
           }
        }
        return redirect()->back()->with('status', 'CSV Upload!');
       
       
    }
    public function uploadcsv(){
        return view('odours.csv');
    }
    public function downloadformat(){
        
   
        $fileName = 'format.zip';
   
       
        return response()->download(public_path($fileName));
    }
    /**
     * Returns the intensity and duration of an odor
     *
     * @return Odor
     */
    public function getSpecifications($id)
    {
        $odour = Odor::where('id', $id)->first();
        $intensity = OdorIntensity::where('id', $odour->id_odor_intensity)->first();
        $odour->intensity = $intensity->name;

        $duration = OdorDuration::where('id', $odour->id_odor_duration)->first();
        $odour->duration = $duration->name;

        return $odour;
    }


    /**
     * Returns the statistics of odors according to the filters applied
     *
     * @return array
     */
    public function getStatistics(Request $request)
    {
        /*Verify this Zone Admin*/
        if(Auth::guard('web')->check()){
        
        $ZonesClass = new ZonesClass();

        $array_zones = $ZonesClass->ArrayZones();

        $types = OdorParentType::get();


        
        $total_odours = Odor::zone($request->get('map'))->published($request->get('publish_date_src'), $request->get('publish_date_dst'))->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')
               ->whereIn('odor_zones.id_zone', $array_zones)->get();
     $arrayin = '';
     $numItems = count($array_zones);
     $i = 0;
        foreach ($array_zones as $key => $value) {
            if(++$i === $numItems) {
                $arrayin = $arrayin.$value;
            } else {
                $arrayin = $arrayin.$value.',';
            }
            
        }

 
        $subtypes = DB::select("select id_odor_type, count(id_odor_type) as count, odor_types.name
               from odors
               INNER JOIN odor_types ON odor_types.id = odors.id_odor_type
               INNER JOIN odor_zones ON odor_zones.id_odor = odors.id
               WHERE odor_zones.id_zone IN (1,12) group by id_odor_type");
            
        } else {

        $types = OdorParentType::get();
        $total_odours = Odor::zone($request->get('map'))->published($request->get('publish_date_src'), $request->get('publish_date_dst'))->get();
        
        $subtypes = DB::select("select id_odor_type, count(id_odor_type) as count, odor_types.name
        from odors
        INNER JOIN odor_types ON odor_types.id = odors.id_odor_type group by id_odor_type");
        }

         $types_intensity = OdorIntensity::get();
        $types_annoy = OdorAnnoy::get();
        $commented = 0;
        $verified = Odor::zone($request->get('map'))->published($request->get('publish_date_src'), $request->get('publish_date_dst'))->where('verified', '1')->get();

        for ($i = 0; $i < sizeof($total_odours); $i++){
            $type = OdorType::where('id', $total_odours[$i]->id_odor_type)->first();
            $parent_type = OdorParentType::where('id', $type->id_odor_parent_type)->first();

            foreach ($types as $t){
                if ($t->id == $parent_type->id){
                    if ($t->count == null ){$t->count = 1;} else {$t->count++;}
                    break;
                }
            }
        }

        for ($i = 0; $i < sizeof($total_odours); $i++){
            $type = OdorIntensity::where('id', $total_odours[$i]->id_odor_intensity)->first();

            foreach ($types_intensity as $t){
                if($t->id == $type->id){
                    if ($t->count == null){$t->count = 1;} else {$t->count++;}
                    break;
                }

            }
        }

        for ($i = 0; $i < sizeof($total_odours); $i++){
            $type = OdorAnnoy::where('id', $total_odours[$i]->id_odor_annoy)->first();

            foreach ($types_annoy as $t){
                if ($t->id == $type->id){
                    if ($t->count == null){$t->count = 1;} else {$t->count++;}
                    break;
                }
            }
        }

        foreach ($total_odours as $t){
            $is_commented = Comment::where('id_odor', $t->id)->first();
            if ($is_commented){$commented++;}
        }
       

        return array('commented' => $commented, 'verified' => sizeof($verified), 'annoy' => $types_annoy, 'types' => $types, 'intensity' => $types_intensity, 'total_odors' => sizeof($total_odours), 'subtypes' => $subtypes);
    }


    /**
     * Returns the statistics of odors according to the zone
     *
     * @return array
     */
    public function getOdorZoneStats()
    {
        if(Auth::guard('web')->check()){
            
            $ZonesClass = new ZonesClass();

            $array_zones = $ZonesClass->ArrayZones();
           

            $zones = Zone::whereIn('id', $array_zones)->get();
            $zone_odor = DB::table('odor_zones')->whereIn('id_zone', $array_zones)->get();
           
            $types = OdorParentType::get();
            $total_odours = Odor::join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')
               ->whereIn('odor_zones.id_zone', $array_zones)->get();

        } else {

            $zones = Zone::get();
            $zone_odor = DB::table('odor_zones')->get();
            $types = OdorParentType::get();
            $total_odours = Odor::get();

            
        }
        
        for($i = 0; $i < sizeof($zone_odor); $i++){
                foreach ($zones as $zone){
                    if ($zone_odor[$i]->id_zone == $zone->id){
                        if ($zone->count == null){$zone->count = 1;} else {$zone->count++;}
                    }
                }
            }

            for ($i = 0; $i < sizeof($total_odours); $i++){
                $type = OdorType::where('id', $total_odours[$i]->id_odor_type)->first();
                $parent_type = OdorParentType::where('id', $type->id_odor_parent_type)->first();

                foreach ($types as $t){
                    if ($t->id == $parent_type->id){
                        if ($t->count == null ){$t->count = 1;} else {$t->count++;}
                        break;
                    }
                }
            }

        return array('types' => $types, 'zones' => $zones, 'total_odors' => sizeof($total_odours), 'total_odors_z' => sizeof($zone_odor));
    }


    /**
     * Changes the state of the odor (published or deactivated)
     */
    public function updateStatus(Request $request)
    {
        $odour = Odor::where('id', $request->get('odour_id'))->first();

        if($odour){
            if ($request->get('status') == 'on'){
                $odour->status = 'published';
            } else {
                $odour->status = 'deactivated';
            }
        }

        $odour->save();

        return redirect()->back();
    }


    /**
     * Returns the comments of an odor
     *
     * @return Comment
     */
    public function comments($id)
    {
        $comments = Comment::where('id_odor', $id)->get();

        if ($comments){
            foreach ($comments as $comment){
                $user = User::where('id', $comment->id_user)->first();
                $comment->user = $user;
                $userTimezone = new DateTimeZone('Europe/Berlin');
                $gmtTimezone = new DateTimeZone('UTC');
                $myDateTime = new DateTime($comment->created_at, $gmtTimezone);
                $offset = $userTimezone->getOffset($myDateTime);
                $myInterval=DateInterval::createFromDateString((string)7200 . 'seconds');
                $myDateTime->add($myInterval);
                $result = $myDateTime->format('Y-m-d H:i:s');
                $comment->created_at = $result;
            }
        }

        return view('odours.comments', ['odour' => $id, 'comments' => $comments]);
    }


    /**
     * Hides a comment of a smell
     */
    public function hideComment($id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->hidden = 1;
        $comment->save();

        return redirect()->route('odour.comments', ['id' => $comment->id_odor]);
    }


    /**
     * Returns the information of the private zones
     *
     * @return Zone
     */
    public function statistics()
    {
       
        /*Verify this Zone Admin*/
        if(Auth::guard('web')->check()){
            
            $ZonesClass = new ZonesClass();

            $array_zones = $ZonesClass->ArrayZones();

            $maps = Zone::whereIn('id', $array_zones)->get();
            
        } else {
             $maps = Zone::get();
        }
       
        return view('statistics', ['maps' => $maps]);
    }


    /**
     * Returns the track from an odour
     *
     * @return OdorTrack
     */
    public function getTrack($id)
    {
        $track = OdorTrack::where('id_odor', $id)->get();
        return $track;
    }

    /**
     * Download the information of the odors according to the filters applied
     */
    public function download(Request $request)
    {
        

        $type = $request->get('type_down');
        $subtype = $request->get('subtype_down');
        $status = $request->get('status_down');
        $intensity = $request->get('intensity_down');
        $annoy = $request->get('annoy_down');
        $map = $request->get('map_down');
        $publish_date_src = $request->get('publish_date_src_down');
        $publish_date_dst = $request->get('publish_date_dst_down');

        $statusSelection;
        
        if(empty($status)){$statusSelection='published';}elseif(!empty($status) && $status === '1'){$statusSelection='published';}elseif(!empty($status) && $status === '2'){$statusSelection='deleted'; $status='';}
         elseif(!empty($status) && $status === '0'){$statusSelection='No';}

        if ($publish_date_dst == '') $publish_date_dst = '';

        /*Verify this Zone Admin*/
        if(Auth::guard('web')->check()){
        $ZonesClass = new ZonesClass();

        $array_zones = $ZonesClass->ArrayZones();
        $zone = Zone::where('id', $map)->first();
        $postalCodeArray = [];
        $i = 0;

        foreach ($array_zones as $key => $value) {
           $zone = Zone::where('id', $value)->first();
           
           if(!empty($zone->postal_code)){
            $postalCodeArray[$i] = $zone->postal_code;
            $i++;
           }
           
        }

        if(!empty($subtype)){
            $odours = Odor::zone($map)->subtype($subtype)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')->select('odors.*')->whereIn('odor_zones.id_zone', $array_zones)->get();

        } else {
            $odours = Odor::zone($map)->type($type)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')->select('odors.*')->whereIn('odor_zones.id_zone', $array_zones)->get();
        }
        
        
   
        } else {
        
        if(!empty($subtype)){
            $odours = Odor::zone($map)->subtype($subtype)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->select('odors.*')->get();
        } else {
            $odours = Odor::zone($map)->type($type)->status($status)->intensity($intensity)->annoy($annoy)->published($publish_date_src, $publish_date_dst)->orderBy('published_at', 'desc')->join('locations', 'locations.id_odor', '=', 'odors.id')->select('odors.*')->get();
        }
        }
       
        

        foreach ($odours as $odour){
  
            if($odour->verified == 1){$odour->verified = 'Yes';} else {$odour->verified = 'No';}

            $user = User::where('id', $odour->id_user)->first();
            $intensity = OdorIntensity::where('id', $odour->id_odor_intensity)->first();
            $duration = OdorDuration::where('id', $odour->id_odor_duration)->first();
            if(empty($duration->name)){
                $name = '';
            } else {
                $name = $duration->name;
            }

            $annoy = OdorAnnoy::where('id', $odour->id_odor_annoy)->first();
            //$location = Location::where('id_odor', $odour->id)->first();
            $location = DB::table('locations')->select('locations.*')->where('id_odor', $odour->id)->first();
            $subtype = OdorType::where('id', $odour->id_odor_type)->first();

            $odorzone = DB::table('odor_zones')->select('odor_zones.*')->where('odor_zones.id_odor', $odour->id)->first();
            
            if(!empty($odorzone)){
                 $zone = Zone::where('id', $odorzone->id_zone)->first();
             } else {
                 $zone = "";
             }

            //$odorzone = OdorZones::where('id_odor', $odour->id)->first();
           
            $type = OdorParentType::where('id', $subtype->id_odor_parent_type)->first();
            if(empty($zone)){$odour->zone = '';} else {$odour->zone = $zone->name;}
            if(empty($user)){$nameuser = '';} else {$nameuser = $user->username;}

            if(empty($user)){$id_user = "";} else {$id_user = $user->id;}
        
            $day = explode(" ", $odour->published_at)[0];
            $time = explode(" ", $odour->published_at)[1];
            
            if($statusSelection === $odour->status || ($status === '0' && $statusSelection === $odor->verified)){
                if((!empty($location->longitude)) && (!empty($location->latitude)) && (!empty($location->address))){
                    if(Auth::guard('web')->check()){
                        $data[] = array (
                            "ID Odour"                                  => $odour->id,
                            "Verified"                                  => $odour->verified,
                            "Type"                                      => $type->name,
                            "Zone"                                      => $odour->zone,
                            "Subtype"                                   => $subtype->name,
                            "Status"                                    => $odour->status,
                            "Description"                               => $odour->description,
                            "Latitude"                                  => $location->latitude,
                            "Longitude"                                 => $location->longitude,
                            "Address"                                   => $location->address,
                            "Origin"                                    => $odour->origin,
                            "Intentity"                                 => $intensity->name,
                            "Duration"                                  => $name,
                            "Annoy"                                     => $annoy->name,
                            "day"                                       => $day,
                            "time"                                      => $time 
                            );
                    } else {
                        $data[] = array (
                            "ID Odour"                                  => $odour->id,
                            "User"                                      => $id_user,
                            "Verified"                                  => $odour->verified,
                            "Type"                                      => $type->name,
                            "Zone"                                      => $odour->zone,
                            "Subtype"                                   => $subtype->name,
                            "Status"                                    => $odour->status,
                            "Description"                               => $odour->description,
                            "Latitude"                                  => $location->latitude,
                            "Longitude"                                 => $location->longitude,
                            "Address"                                   => $location->address,
                            "Origin"                                    => $odour->origin,
                            "Intentity"                                 => $intensity->name,
                            "Duration"                                  => $name,
                            "Annoy"                                     => $annoy->name,
                            "day"                                       => $day,
                            "time"                                      => $time 
                            );
                    }
            

            
                } else if(!empty($location->address)){

                    if(empty($zone)){$odour->zone = '';} else {$odour->zone = $zone->name;}

                    if(Auth::guard('web')->check()){
                        $data[] = array (
                            "ID Odour"                                  => $odour->id,
                            "Verified"                                  => $odour->verified,
                            "Type"                                      => $type->name,
                            "Zone"                                     => $odour->zone,
                            "Type"                                      => $type->name,
                            "Subtype"                                   => $subtype->name,
                            "Status"                                    => $odour->status,
                            "Description"                               => $odour->description,
                            "Latitude"                                  => $location->latitude,
                            "Longitude"                                 => $location->longitude,
                            "Address"                                   => $location->address,
                            "Origin"                                    => $odour->origin,
                            "Intentity"                                 => $intensity->name,
                            "Duration"                                  => $name,
                            "Annoy"                                     => $annoy->name,
                            "day"                                       => $day,
                            "time"                                      => $time 
                            
                        );
                    } else {
                        $data[] = array (
                            "ID Odour"                                  => $odour->id,
                            "User"                                      => $id_user,
                            "Verified"                                  => $odour->verified,
                            "Type"                                      => $type->name,
                            "Zone"                                     => $odour->zone,
                            "Type"                                      => $type->name,
                            "Subtype"                                   => $subtype->name,
                            "Status"                                    => $odour->status,
                            "Description"                               => $odour->description,
                            "Latitude"                                  => $location->latitude,
                            "Longitude"                                 => $location->longitude,
                            "Address"                                   => $location->address,
                            "Origin"                                    => $odour->origin,
                            "Intentity"                                 => $intensity->name,
                            "Duration"                                  => $name,
                            "Annoy"                                     => $annoy->name,
                            "day"                                       => $day,
                            "time"                                      => $time 
                            
                        );
                    }
                
                } else {
                
                    
                    if(isset($location->latitude)){
                
                        if(Auth::guard('web')->check()){
                            $data[] = array (
                                "ID Odour"                                  => $odour->id,
                                "Verified"                                  => $odour->verified,
                                "Type"                                      => $type->name,
                                "Zone"                                     => $odour->zone,
                                "Type"                                      => $type->name,
                                "Subtype"                                   => $subtype->name,
                                "Status"                                    => $odour->status,
                                "Description"                               => $odour->description,
                                "Latitude"                                  => $location->latitude,
                                "Longitude"                                 => $location->longitude,
                                "Address"                                   => "",
                                "Origin"                                    => $odour->origin,
                                "Intentity"                                 => $intensity->name,
                                "Duration"                                  => $name,
                                "Annoy"                                     => $annoy->name,
                                "day"                                       => $day,
                                "time"                                      => $time
                            ); 
                        } else {
                            $data[] = array (
                                "ID Odour"                                  => $odour->id,
                                "User"                                      => $id_user,
                                "Verified"                                  => $odour->verified,
                                "Type"                                      => $type->name,
                                "Zone"                                     => $odour->zone,
                                "Type"                                      => $type->name,
                                "Subtype"                                   => $subtype->name,
                                "Status"                                    => $odour->status,
                                "Description"                               => $odour->description,
                                "Latitude"                                  => $location->latitude,
                                "Longitude"                                 => $location->longitude,
                                "Address"                                   => "",
                                "Origin"                                    => $odour->origin,
                                "Intentity"                                 => $intensity->name,
                                "Duration"                                  => $name,
                                "Annoy"                                     => $annoy->name,
                                "day"                                       => $day,
                                "time"                                      => $time 
                            ); 
                        }
                        
                    }
                }
            }
        }
       
        $name_file = 'odours';
       
    
        Excel::create($name_file, function($excel) use($data){
            $excel->sheet('Odours', function($sheet) use($data){
                $sheet->fromArray( $data );
            });
        })->download('xlsx');
    }

    public function attach(Request $request){
        $client = new Client();
        
        try {
            $result = $client->get(env('API_URL') . 'attachOdourToZone', [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ]
            ]);

            return redirect()->route('odour.list')->withInput()->withErrors(['attach']);

        } catch ( RequestException $e ) {

            $message = json_decode( $e->getResponse()->getBody()->getContents() );

            return redirect()->route('odour.list')->withInput()->withErrors(['attach_fail']);
        }
    }
    public function track(Request $request){
        
        $type = $request->get('type_down');
        $status = $request->get('status_down');
        $intensity = $request->get('intensity_down');
        $annoy = $request->get('annoy_down');
        $map = $request->get('map_down');
        $publish_date_src = $request->get('publish_date_src_down');
        $publish_date_dst = $request->get('publish_date_dst_down');

        $odors = DB::table('odor_zones')
        ->select('odors.*', 'zones.name as name_zone', 'odors.name as name_odor', 'odors.origin as origin')
        ->join('odors', 'odors.id', '=', 'odor_zones.id_odor')
        ->join('zones', 'zones.id', '=', 'odor_zones.id_zone')
        ->where('odor_zones.id_zone', $request->id)
        //->where('odors.track', '1')
        ->get();


       $i = 1;
        $y=1;
        foreach ($odors as $key => $odor) {
            $locations = Location::where('id_odor', $odor->id)->get();
            foreach ($locations as $key => $location) {
                $datas[$i] = array(
                'count' => $y,
                'odor_id' => $location->id_odor,
                'id_track' => '',
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'time' => $location->created_at->format('Y-m-d G:i:s')
            );
             $i++;
             $y++;
            }
           $tracks = OdorTrack::where('id_odor', $odor->id)->limit(4)->get();
           foreach ($tracks as $key => $track) {
                $datas[$i] = array(
                'count' => $y,
                'odor_id' => $track->id_odor,
                'id_track' => $track->id,
                'latitude' => $track->latitude,
                'longitude' => $track->longitude,
                'time' => $track->time
            );
                 $i++;
                 $y++;
           
               # code...
           }
           $y=1;
            

           
        }
        
    
        $name_file = 'tracks';
        Excel::create($name_file, function($excel) use($datas){
            $excel->sheet('Odours', function($sheet) use($datas){
                $sheet->fromArray( $datas );
            });
        })->download('xlsx');

        return redirect()->route('zone.show', ['id' => $request->id])->withInput()->withErrors(['attach']);
    }

   
}
