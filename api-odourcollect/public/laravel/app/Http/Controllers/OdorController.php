<?php

namespace App\Http\Controllers;

use App\Email;
use App\Http\Controllers\LocationController;
use App\Mail\AdminMail;
use App\OdorEmail;
use App\OdorTrack;
use App\Services\PointLocation;
use App\Stat;
use App\Zone;
use Illuminate\Http\Request;

use App\Odor;
use App\Location;
use App\User;
use App\Like;
use App\Comment;
use App\OdorType;
use App\OdorAnnoy;
use App\OdorIntensity;
use App\OdorParentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

use App\Services\OdorColor;

class OdorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		
		$filters = [];
		
		/*
			'type' filter => Corresponds to the parent type
		*/		
		$filter_type=$request->input("type");

		if($filter_type){
            $filters[ 'id_odor_parent_type']= $filter_type;
        }
        /*
            'subtype' filter => Corresponds to the specific type
        */		
        $filter_subtype=$request->input("subtype");

        if($filter_subtype){
            $filters[ 'id_odor_type']= $filter_subtype;
        }
        
        $filter_minIntensity=$request->input("minIntensity");
        $filter_maxIntensity=$request->input("maxIntensity");			
        if(!$filter_minIntensity){
            $filter_minIntensity=0;
        }
        if(!$filter_maxIntensity){
            $filter_maxIntensity=6;
        }

        $filter_minAnnoy=$request->input("minAnnoy");
        $filter_maxAnnoy=$request->input("maxAnnoy");        
        if(!$filter_minAnnoy){
            $filter_minAnnoy=-4;
        }

        if(!$filter_maxAnnoy){
            $filter_maxAnnoy=4;
        }

        $filter_date_init=$request->input("date_init");
        if(!$filter_date_init){
            $filter_date_init="2000-01-01 00:00:01"; 
        }
        
        $filter_date_end=$request->input("date_end");        
        if(!$filter_date_end){
            $filter_date_end="3000-01-01 00:00:01"; 
        }

        $odours = DB::table('odors')
            ->join('locations','odors.id','=','locations.id_odor')
            ->join('odor_types','odors.id_odor_type','=','odor_types.id')
            ->select('odors.id', 'id_odor_type', 'id_user', 'odors.color', 'id_odor_intensity', 'id_odor_duration', 'id_odor_annoy', 'published_at','latitude','longitude')
            ->where($filters)
            ->where('id_odor_intensity', '>=', ($filter_minIntensity + 1)) //id=1 power=0
            ->where('id_odor_intensity', '<=', ($filter_maxIntensity + 1)) 
            ->where('id_odor_annoy', '>=', ($filter_minAnnoy + 5)) //id=1 index=-4
            ->where('id_odor_annoy', '<=', ($filter_maxAnnoy + 5))
            ->where('published_at', '>=', $filter_date_init) //id=1 index=-4
            ->where('published_at', '<=', $filter_date_end)
            ->where('status', '=', "published")
            ->where('verified', '=', 1)
            
        ->get();

        
        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'date_init' => $request->get('date_init'),
                'message' => 'Succesfull request.',
                'content' => $odours,
            ]
        ], 200);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function retro()
    {
	    $odours = DB::statement('select locations.id_odor, group_concat(odor_tracks.latitude SEPARATOR '/'), group_concat(odor_tracks.longitude SEPARATOR '/'), locations.latitude , locations.longitude   from odor_tracks, locations where odor_tracks.id_odor = locations.id_odor group by locations.id_odor limit 10')
	    ->get();
	    return response() ->json(
		    [
			    'status_code' => 200,
			    'data' => [
				    'message' => 'OK',
				    'content' => $odours,
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

        $odorColor = new OdorColor();

        $location_crtl = new LocationController();
        $location_added = $location_crtl->validation($request);

        $validator = Validator::make($request->all(), [
            'id_odor_type' => 'required|not_in:0',
            'id_user' => 'required|not_in:0',
            'name' => 'required|max:180',
            'description' => 'max:1024',
            'origin' => 'max:180',
            'id_odor_intensity' => 'required|not_in:0',
            'id_odor_annoy' => 'required|not_in:0',
        ]);

        if ( $validator->passes() && $location_added['result'] == true ) {

            $user = User::find($request->get('id_user'));
            $odor = new Odor($request->all());
            $odor->other = $request->other;
            $odor->published_at = date("Y-m-d H:i:s");
            $odor->slug = $this->generateSlug($request->get('name'));
            $odor->verified = 1;


            $annoy = OdorAnnoy::find($request->get('id_odor_annoy'));
            if($annoy){
                $intensity = OdorIntensity::find($request->get('id_odor_intensity'));
                if($intensity){
                    $odor->color = $odorColor->getOdorColor($annoy->index, $intensity->power);
                }
            }

            $odor->save();

            $location = $location_crtl->getLocationInfo($request);
            $location->id_odor = $odor->id;
            $location->save();

            $odor->location = $location;

                       
           # $email_admin = new OdorEmail();
           # $email_admin->user = $user->name . ' ' . $user->surname;
           # $email_admin->subject = 'New odour entry';
           # $email_admin->location = $location->place;
           # $email_admin->save(); 
            

           # Mail::to("odourcollect@ibercivis.es")->send(new AdminMail($email_admin));

            $this->zoneAttach($odor->id, $user->without_validation);

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'created' => true,
                    'message' => "Odor ".$odor->id." with Location ".$location->id." has been created.",
                    'object' => $odor,                    
                ]
            ], 200);
        }

        if($location_added['result'] == true){
            $errors = [];
        }else{
            $errors = $location_added['errors'];
        }

        $aux = $validator->errors();
        if ($aux->has('id_odor_type')) { $errors['id_odor_type'] = $aux->get('id_odor_type'); }
        if ($aux->has('id_user')) { $errors['id_user'] = $aux->get('id_user'); }
        if ($aux->has('name')) { $errors['name'] = $aux->get('name'); }
        if ($aux->has('description')) { $errors['description'] = $aux->get('description'); }
        if ($aux->has('origin')) { $errors['origin'] = $aux->get('origin'); }
        if ($aux->has('id_odor_intensity')) { $errors['id_odor_intensity'] = $aux->get('id_odor_intensity'); }
        if ($aux->has('id_odor_duration')) { $errors['id_odor_duration'] = $aux->get('id_odor_duration'); }
        if ($aux->has('id_odor_annoy')) { $errors['id_odor_annoy'] = $aux->get('id_odor_annoy'); }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'created' => false,
                'message' => "There are some errors in the form data.",
                'errors' => $errors,
            ]
        ], 400);
    }

    

    /**
     * Display the specified resource.
     *
     * @param  Integer  $odor
     * @return \Illuminate\Http\Response
     */
    public function show($odor)
    {

        $oddour = Odor::with('location')->with('likes')->with('comments')->verified('1')->status('published')->find($odor);

        if($oddour){

            $oddour->user = null;
            if($oddour->id_user != 0 && $oddour->id_user != null){
                $oddour->user = User::findOrFail($oddour->id_user);
            }
            $oddour->confirmations = $oddour->likes->count();
            unset($oddour->likes);
            unset($oddour->user->email);
            unset($oddour->user->name);
            unset($oddour->user->surname);
            unset($oddour->user->age);
            unset($oddour->user->phone);
             unset($oddour->user->type);

            $oddour->name_odor_type = null;
            $oddour->id_odor_parent_type = 0;
            $oddour->name_odor_parent_type = null;

            $type = OdorType::find($oddour->id_odor_type);
            if($type){
                $oddour->name_odor_type = $type->name;
                $oddour->id_odor_parent_type = $type->id_odor_parent_type;
                $parent = OdorParentType::find($type->id_odor_parent_type);
                if($parent){
                    $oddour->name_odor_parent_type = $parent->name;
                }
            }

            /*$odorColor = new OdorColor();
            $oddour->color = false;

            $annoy = OdorAnnoy::find($oddour->id_odor_annoy);
            if($annoy){
                $intensity = OdorIntensity::find($oddour->id_odor_intensity);
                if($intensity){
                    $oddour->color = $odorColor->getOdorColor($annoy->index, $intensity->power);
                }
            } */

            $stat = new Stat();
            $stat->type = 'Odor';
            $stat->id_target = $oddour->id;
            $stat->id_user = $oddour->id_user;
            $stat->save();

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'finded' => true,
                    'message' => "Odor ".$odor." has been finded.",
                    'object' => $oddour,
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Odor  $odor
     * @return \Illuminate\Http\Response
     */
    public function edit(Odor $odor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $odor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $odor)
    {

        $oddour = Odor::with('location')->with('likes')->find($odor);

        if($oddour){

            $validator = Validator::make($request->all(), [
                'id_odor_type' => 'required|not_in:0',
                'id_user' => 'required|not_in:0',
                'name' => 'required|max:180',
                'description' => 'required|max:1024',
                'id_location' => 'required|not_in:0',
                'id_odor_intensity' => 'required|not_in:0',
                'id_odor_duration' => 'required|not_in:0',
                'id_odor_annoy' => 'required|not_in:0',
            ]);

            if ($validator->passes()) {
                $oddour->update($request->all());;
                $oddour->slug = $this->generateSlug($request->get('name'));
                $oddour->save();

                $oddour->user = null;
                if($oddour->id_user != 0 && $oddour->id_user != null){
                    $oddour->user = User::findOrFail($oddour->id_user);
                }
                $oddour->confirmations = $oddour->likes->count();

                return response()->json(
                [
                    'status_code' => 200,
                    'data' => [
                        'updated' => true,
                        'message' => "Odor ".$oddour->id." has been updated.",
                        'object' => $oddour,
                    ]
                ], 200);
            }

            $aux = $validator->errors();
            $errors = [];
            if ($aux->has('id_odor_type')) { $errors['id_odor_type'] = $aux->get('id_odor_type'); }
            if ($aux->has('id_user')) { $errors['id_user'] = $aux->get('id_user'); }
            if ($aux->has('name')) { $errors['name'] = $aux->get('name'); }
            if ($aux->has('description')) { $errors['description'] = $aux->get('description'); }
            if ($aux->has('id_location')) { $errors['id_location'] = $aux->get('id_location'); }
            if ($aux->has('id_odor_intensity')) { $errors['id_odor_intensity'] = $aux->get('id_odor_intensity'); }
            if ($aux->has('id_odor_duration')) { $errors['id_odor_duration'] = $aux->get('id_odor_duration'); }
            if ($aux->has('id_odor_annoy')) { $errors['id_odor_annoy'] = $aux->get('id_odor_annoy'); }

            return response()->json(
            [
                'status_code' => 400,
                'data' => [
                    'updated' => false,
                    'message' => "There are some errors in the form data.",
                    'errors' => $errors,
                ]
            ], 400);
        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'updated' => false,
                'message' => "Resource not found.",
            ]
        ], 400);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $odor
     * @return \Illuminate\Http\Response
     */
    public function destroy($odor)
    {   
        $oddour = Odor::find($odor);

        if($oddour){

            $oddour->delete();

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'deleted' => true,
                    'message' => "Odor ".$odor." has been deleted.",
                    'object' => $oddour,
                ]
            ], 200);
        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'deleted' => false,
                'message' => "Resource not found.",
            ]
        ], 400);
    }

    /**
     * Add Like to resource.
     *
     * @param  Integer  $odor
     * @param  Integer  $user
     * @return \Illuminate\Http\Response
     */
    public function confirm($odor_id, $user_id)
    {   
        $id_like = 1;

        $odor = Odor::find($odor_id);
        $user = User::find($user_id);

        if($odor && $user){

            $like = Like::user($user_id)->odor($odor_id)->first();

            if(!$like){

                $confirm = new Like();
                $confirm->id_user = $user_id;
                $confirm->id_odor = $odor_id;
                $confirm->id_like_type = 1;
                $confirm->save();

                return response()->json(
                [
                    'status_code' => 200,
                    'data' => [
                        'confirmed' => true,
                        'message' => "User(".$user_id.") has confirmed Odor(".$odor_id.").",
                    ]
                ], 200);
            }

            return response()->json(
            [
                'status_code' => 400,
                'data' => [
                    'confirmed' => false,
                    'message' => "User(".$user_id.") has already confirmed Odor(".$odor_id.").",
                ]
            ], 400);

            
        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'confirmed' => false,
                'message' => "Resource not found.",
            ]
        ], 400);
    }

    public function delete($odor_id)
    {
        $odor = Odor::find($odor_id);

        if ($odor){
            $odor->status = 'deleted';
            $odor->save();

            return response()->json(
                [
                    'status_code' => 200,
                    'data' => [
                        'deleted' => true,
                        'message' => "Odor(".$odor_id.") deleted",
                    ]
                ], 200);
        }
        return response()->json(
            [
                'status_code' => 400,
                'data' => [
                    'deleted' => false,
                    'message' => "Resource not found.",
                ]
            ], 400);
    }

    /**
     * Add Like to resource.
     *
     * @param  Integer  $odor
     * @param  Integer  $user
     * @return \Illuminate\Http\Response
     */
    public function unconfirm($odor_id, $user_id)
    {   
        $id_like = 1;

        $odor = Odor::find($odor_id);
        $user = User::find($user_id);

        if($odor && $user){

            $like = Like::user($user_id)->odor($odor_id)->first();

            if(!$like){
                return response()->json(
                [
                    'status_code' => 400,
                    'data' => [
                        'confirmed' => false,
                        'message' => "User(".$user_id.") has no confirmed Odor(".$odor_id.").",
                    ]
                ], 400);
            }


            $like->delete();

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'confirmed' => true,
                    'message' => "User(".$user_id.") has unconfirmed Odor(".$odor_id.").",
                ]
            ], 200);

  
        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'confirmed' => false,
                'message' => "Resource not found.",
            ]
        ], 400);
    }

    /**
     * Add Like to resource.
     *
     * @param  Integer  $odor
     * @param  Integer  $user
     * @return \Illuminate\Http\Response
     */
    public function isConfirmed($odor_id, $user_id)
    {   
        $id_like = 1;

        $odor = Odor::find($odor_id);
        $user = User::find($user_id);
        
        if($odor && $user){

            $like = Like::user($user_id)->odor($odor_id)->first();

            if(!$like){

                return response()->json(
                [
                    'status_code' => 200,
                    'data' => [
                        'confirmed' => false,
                        'message' => "User(".$user_id.") has not confirmed Odor(".$odor_id.").",
                    ]
                ], 200);
            }

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'confirmed' => true,
                    'message' => "User(".$user_id.") has confirmed Odor(".$odor_id.").",
                ]
            ], 200); 
        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'confirmed' => false,
                'message' => "Resource not found.",
            ]
        ], 400);
    }

    /**
     * Get odor comments
     *
     * @param  Integer  $odor
     * @return \Illuminate\Http\Response
     */
    public function comments($odor_id)
    {   

        $odor = Odor::find($odor_id);

        if($odor){

            $comments = Comment::with('user')->odor($odor_id)->where('hidden', 0)->get();

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'confirmed' => true,
                    'message' => "Odor(".$odor_id.") comments found.",
                    'object' => $comments,
                ]
            ], 200);

        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'confirmed' => false,
                'message' => "Resource not found.",
            ]
        ], 400);
    }

    public function zoneAttach($id, $verification)
    {

        $pointLocation = new pointLocation();

        $odour = Odor::with('location')->where('id', $id)->first();

        $odor_location = $odour->location->latitude.' '.$odour->location->longitude;

        $zones = Zone::with('points')->get();


        foreach ($zones as $key => $zone) {
            $polygon = [];
            foreach ($zone->points as $key => $point) {
                $aux = '';
                $aux = $point->latitude. ' ' .$point->longitude;
                $polygon[] = $aux;
            }

            $result = $pointLocation->pointInPolygon($odor_location, $polygon);
          
            if($result != 0){
                //is inside the zone

                $verified = 0;
                $user = User::with('zones')->find($odour->id_user);
                /*
                if($user){
                    foreach ($user->zones as $key => $z) {
                        if($z->id = $zone->id){
                            $odour->verified = 0;
                        }
                    }
                } 

                if ($verification == 1){
                    $odour->verified = 1;
                }
                */

                $odour->zones()->detach($zone->id);
                $odour->zones()->attach($zone->id, ['verified' => 0]);
                #$user_belong_to_zone = DB::table('user_zones')->where('id_user', $user->id)->where('id_zone', $zone->id)->first();

                #if ($user_belong_to_zone){
                #    $odour->zones()->detach($zone->id);
                #    $odour->zones()->attach($zone->id, ['verified' => $verified]);
                #} else {
                #    $odour->zones()->detach($zone->id);
                #}
            }
        }
    }

    public function attachOdourToZone(){
        $odours = Odor::get();

        $zones = Zone::get();

        foreach ($zones as $zone){

            foreach ($odours as $odour){
                $pointLocation = new pointLocation();
                if(!empty($odour->location->latitude)){
                    $odor_location = $odour->location->latitude.' '.$odour->location->longitude;
                    $polygon = [];
                    foreach ($zone->points as $key => $point) {
                        $aux = '';
                        $aux = $point->latitude. ' ' .$point->longitude;
                        $polygon[] = $aux;
                    }

                    $result = $pointLocation->pointInPolygon($odor_location, $polygon);

                    if($result != 0){
                        $odour->zones()->detach($zone->id);
                        $odour->zones()->attach($zone->id, ['verified' => 0]);
                    }
                }
              

                
            }
        }
    }

    public function prolorInformation(){

        $odors = Odor::with('zones')->where('track', 0)->get();
        $time = '';
        $date = '';

        foreach ($odors as $odor){
            $loc = Location::where('id_odor', $odor->id)->first();
            $time = $odor->published_at;
            $time = explode(" ", $time);
            $date = $time[0];
            $time = explode(":", $time[1]);
            $time = intval($time[0]);

            if ($loc){
                $url = 'https://airadvanced.net/airadvanced/retros/novaretro/' . $date . '/' . $time . '/' . $loc->latitude .'/' . $loc->longitude . '/';
                //$url = 'https://www.troposfera.es/siami/retros/novaretro/' . $date . '/' . $time . '/' . $loc->latitude .'/' . $loc->longitude . '/';
//                $url = 'https://www.troposfera.es/siami/retros/novaretro/2019-04-04/1/41.3859000/2.1675000/';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
                $response = curl_exec($ch);
                curl_close($ch);

                $result = json_decode($response);

                if(count((array)$result) > 1){
                    foreach ($result->features as $f) {
                        $track = new OdorTrack();
                        $track->id_odor = $odor->id;
                        $track->latitude = $f->geometry->coordinates[1];
                        $track->longitude = $f->geometry->coordinates[0];
                        $track->time = $f->properties->horas;
                        $track->save();

                        $odor->track = 1;
                        $odor->save();
                    }
                }
            }
        }
    }

    private function generateSlug($str){
    # special accents
    $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
    $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
    return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
}
}
