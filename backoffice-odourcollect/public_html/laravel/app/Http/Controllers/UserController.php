<?php

namespace App\Http\Controllers;

use App\Email;
use App\Like;
use App\Location;
use App\Mail\EmailUser;
use App\Odor;
use App\OdorAnnoy;
use App\OdorDuration;
use App\OdorIntensity;
use App\OdorParentType;
use App\OdorType;
use App\Stat;
use App\User;
use App\UserZone;
use App\Zone;
use App\Admin;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Classes\ZonesClass;

class UserController extends Controller
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
     * Show the list of unverified odors, some statistics and top5 odors (dashboard)
     *
     * @return array
     */
    public function home()
    {   
        /*Verify this Zone Admin*/
        if(Auth::guard('web')->check()){
            
            $ZonesClass = new ZonesClass();


            $array_zones = $ZonesClass->ArrayZones();
          
               $odours = DB::table('odors')
               ->select('odors.*', 'odor_zones.id_zone')
               ->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')
               ->whereIn('odor_zones.id_zone', $array_zones)
               ->where('odors.verified', 0)
               ->orderBy('published_at', 'desc')->get();

        $count_users = 0;

        $users = User::join('user_zones', 'user_zones.id_user', '=', 'users.id')->whereIn('user_zones.id_zone', $array_zones)->count(); 

        $count_confirmations = DB::table('likes')->count();
               
        
        $us = User::join('user_zones', 'user_zones.id_user', '=', 'users.id')->whereIn('user_zones.id_zone', $array_zones)->get(); 

        $top5 = DB::table('stats')
        ->select('id_target', DB::raw('count(*) as count' ))
        ->join('user_zones', 'user_zones.id_user', '=', 'stats.id_user')
        ->whereIn('user_zones.id_zone', $array_zones)
        ->where('type', 'Odor')
        ->groupBy('id_target')
        ->orderBy('count', 'desc')
        ->limit(5)->get();       
             
        } else {
        
        $odours = Odor::where('verified', 0)->orderBy('published_at', 'desc')->get();

        $count_users = 0;

        $users = User::join('user_zones', 'user_zones.id_user', '=', 'users.id')->count();

        $count_confirmations = DB::table('likes')->count();
               
        
        $us = User::join('user_zones', 'user_zones.id_user', '=', 'users.id')->get();

        $top5 = DB::table('stats')
        ->select('id_target', DB::raw('count(*) as count' ))
        ->join('user_zones', 'user_zones.id_user', '=', 'stats.id_user')
        ->where('type', 'Odor')
        ->groupBy('id_target')
        ->orderBy('count', 'desc')
        ->limit(5)->get();
        
        }

        foreach ($odours as $odour){

                $user = User::where('id', $odour->id_user)->first();
                if(!empty($user->username)){
                     $odour->user = $user->username;
                 } else {
                     $odour->user = "";
                 }
               

                $type = OdorType::where('id', $odour->id_odor_type)->first();
                $odour->odour_type = $type->name;

                $address = Location::where('id_odor', $odour->id)->first();
                if (isset($address->address)){
                $odour->address = $address->address;
                } else{
                    $odour->address = "";
                }

                $userTimezone = new DateTimeZone('CEST');
                $gmtTimezone = new DateTimeZone('UTC');
                $myDateTime = new DateTime($odour->published_at, $gmtTimezone);
                $myInterval=DateInterval::createFromDateString((string)7200 . 'seconds');
                $myDateTime->add($myInterval);
                $result = $myDateTime->format('Y-m-d H:i:s');
                $odour->published_at = $result;
            }

        

        

        foreach ($us as $u){
            $count_users = $count_users + $u->count;
        }

        

        foreach ($top5 as $t){

            $odour = Odor::where('id', $t->id_target)->first();

            if($odour['origin'] == null){
                $t->origin = '-';
            } else {
                $t->origin = $odour['origin'];
            }


            $type = OdorType::where('id', $odour['id_odor_type'])->first();
            $parent_type = OdorParentType::where('id', $type['id_odor_parent_type'])->first();
            $t->type = $type['name'];
            $t->parent_type = $parent_type['name'];

            $intensity = OdorIntensity::where('id', $odour['id_odor_intensity'])->first();
            $t->intensity = $intensity['name'];

            $annoy = OdorAnnoy::where('id', $odour['id_odor_annoy'])->first();
            $t->annoy = $annoy['name'];

            $duration = OdorDuration::where('id', $odour['id_odor_duration'])->first();
            $t->duration = $duration['name'];
        }
       

           
        return view('dashboard', ['odours' => $odours, 'count_users' => $users, 'count_confirmation' => $count_confirmations, 'count_user' => $count_users, 'top' => $top5]);

    }

    /**
     * Show the user list according to the filter applied
     *
     * @return array
     */
    public function index (Request $request)
    {
        if(Auth::guard('web')->check()){
        
        $ZonesClass = new ZonesClass();


        $array_zones = $ZonesClass->ArrayZones();
            

        $status = $request->get('status');
        $permission = $request->get('permission');
        $map = $request->get('map');
        $register_date_src = $request->get('register_date_src');
        $register_date_dst = $request->get('register_date_dst');
        $publish_date_src = $request->get('publish_date_src');
        $publish_date_dst = $request->get('publish_date_dst');

        if ($register_date_src == null) $register_date_src = '';
        if ($register_date_dst == null) $register_date_dst = '';
        if ($publish_date_src == null) $publish_date_src = '';
        if ($publish_date_dst == null) $publish_date_dst = '';

        /*$users = User::select('users.*', 'users.id as id_user_real', 'user_zones.id_zone', 'user_zones.id_user', 'user_zones.deleted_at')->join('user_zones', 'user_zones.id_user', '=', 'users.id')->status($status)->permission($permission)->map($map)->registered($register_date_src, $register_date_dst)->publishing($publish_date_src, $publish_date_dst)->whereIn('user_zones.id_zone', $array_zones)->where('user_zones.deleted_at', NULL)->groupBy('user_zones.id_user')->where('type', 'User')->get();*/

        $users = User::select('users.*', 'users.id as id_user_real')->status($status)->permission($permission)->map($map)->registered($register_date_src, $register_date_dst)->publishing($publish_date_src, $publish_date_dst)->where('type', 'User')->get();
       
        

       $maps = Zone::whereIn('id', $array_zones)->get();

        } else {

        $status = $request->get('status');
        $permission = $request->get('permission');
        $map = $request->get('map');
        $register_date_src = $request->get('register_date_src');
        $register_date_dst = $request->get('register_date_dst');
        $publish_date_src = $request->get('publish_date_src');
        $publish_date_dst = $request->get('publish_date_dst');

        if ($register_date_src == null) $register_date_src = '';
        if ($register_date_dst == null) $register_date_dst = '';
        if ($publish_date_src == null) $publish_date_src = '';
        if ($publish_date_dst == null) $publish_date_dst = '';

         $users = User::select('users.*')->status($status)->permission($permission)->map($map)->registered($register_date_src, $register_date_dst)->publishing($publish_date_src, $publish_date_dst)->get();

        

        $maps = Zone::get();
        }

        return view('users.list', ['users' => $users, 'maps' => $maps]);
    }


    /**
     * Show the user information
     *
     * @return User
     */
    public function show($id)
    {
        if(Auth::guard('web')->check()){
        
        $ZonesClass = new ZonesClass();

        $array_zones = $ZonesClass->ArrayZones(); 

        $user = User::select('users.*')->where('users.id', $id)->where('type', 'User')->first();

        $zones = Zone::whereIn('id', $array_zones)->get();

        } else {

        $user = User::where('id', $id)->first();

        $zones = Zone::get();

        }
        if(empty($user->id)):
        return redirect()->route('user.list')->withErrors(['error']);
        endif;   
        
        $user->last_login = date('jS \\of F', strtotime($user->last_login));

        $visits = Stat::where('type', 'Odor')->where('id_user', $id)->get();
        $user->visits = sizeof($visits);

        $odours = Odor::where('id_user', $id)->get();
        foreach ($odours as $odour){
            $location = Location::where('id_odor', $odour->id)->first();
            if (isset($address->address)){
                $odour->address = $address->address;
            } else{
                $odour->address = "";
            }

            $type = OdorType::where('id', $odour->id_odor_type)->first();
            $odour->odour_type = $type->name;

            $userTimezone = new DateTimeZone('Europe/Berlin');
            $gmtTimezone = new DateTimeZone('UTC');
            $myDateTime = new DateTime($odour->published_at, $gmtTimezone);
            $myInterval=DateInterval::createFromDateString((string)7200 . 'seconds');
            $myDateTime->add($myInterval);
            $result = $myDateTime->format('Y-m-d H:i:s');
            $odour->published_at = $result;
        }

        $user->odours = $odours;

       
        foreach($zones as $zone){

            $zone->belong = false;
            $belong_zone = DB::table('user_zones')->where('id_user', $id)->where('id_zone', $zone->id)->orderBy('id', 'desc')->first();

            if ($belong_zone){
                if ($belong_zone->deleted_at == NULL){$zone->belong = true;}
            }
        }
        $user->zones = $zones;

        $comments = DB::table('comments')->where('id_user', $id)->get();
        $user->comments = sizeof($comments);

        $confirmations = Like::where('id_user', $id)->get();
        $user->confirmations = sizeof($confirmations);

        $emails = Email::where('id_user', $id)->orderBy('created_at', 'desc')->get();

        $user->emails = $emails;



        $zones_user = DB::table('user_zones')->where('id_user',  $user->id)->where('user_zones.deleted_at',  NULL)->join('zones', 'zones.id', '=', 'user_zones.id_zone')->get();
          
       
        return view('users.show', ['user' => $user, 'zones_user' => $zones_user]);
        
    }


    /**
     * Show the create user form
     */
    public function create()
    {
        if(Auth::guard('web')->check()){
        
        $ZonesClass = new ZonesClass();

        $array_zones = $ZonesClass->ArrayZones(); 

        if(!empty($array_zones)):
        $zones = Zone::whereIn('id', $array_zones)->get();

        return view('users.create', ['zones' => $zones]);
        else:
        return redirect()->route('user.list')->withErrors(['error']);
        endif;
       

        } else {
            return view('users.create');
        }
        
    }


    /**
     * Save the new user information from the form
     */
    public function save(Request $request)
    {
        $repited = User::where('email',$request->get('email'))->first();

        if (!$repited) {
            
            if(Auth::guard('web')->check()){
                $user = new User($request->all());
                $user->password = Hash::make($request->get('password'));
                $user->save();

                $user_zone = new UserZone();
                $user_zone->id_zone = $request->get('zone');
                $user_zone->id_user = $user->id;
                $user_zone->save();
                 return redirect()->route('user.create')->withSuccess('User created succesfully!');
            } else {
                $user = new User($request->all());
                $user->password = Hash::make($request->get('password'));
                $user->save();
                 return redirect()->route('admin.user.create')->withSuccess('User created succesfully!');
            }
            

           
        }
        if(Auth::guard('web')->check()){
        return redirect()->route('user.create')->withErrors(['error']);
        } else {
        return redirect()->route('admin.user.create')->withErrors(['error']);
        }
    }


    /**
     * Update the user personal information from the form
     */
    public function update(Request $request)
    {
        $user = User::where('id',$request->get('id'))->first();

        if ($user) {

        if(Auth::guard('web')->check()){

            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->save();

            return redirect()->route('user.show', ['id' => $request->get('id')])->withSuccess('User updated succesfully!');
        
        } else {
            
            $user->username = $request->get('username');
          
            $user->type = $request->get('type');
            $user->email = $request->get('email');
            $user->save();

            
            

        $zones_users = UserZone::where('id_user',  $user->id)->where('user_zones.deleted_at',  NULL)->get();


            foreach ($zones_users as $key => $zone_users) {
                if(!empty($request->zone)){
                     if (in_array($zone_users->id_zone, $request->zone)) {
                        $zone_users->admin = 1;
                        $zone_users->save();
                    } else {
                        $zone_users->admin = 0;
                        $zone_users->save();
                    }   
                } else {
                    $zone_users->admin = 0;
                    $zone_users->save();
                }
                
            }
        
        
    


            return redirect()->route('admin.user.show', ['id' => $request->get('id')])->withSuccess('User updated succesfully!');
        }

        }
        

        return redirect()->route('user.show', ['id' => $request->get('id')])->withErrors(['error']);
    }


    /**
     * Update the user password from the form
     */
    public function updatePassword(Request $request)
    {
        $user = User::where('id',$request->get('id_user'))->first();

        if ($user) {
            $user->password = Hash::make($request->get('password'));
            $user->save();

            return redirect()->route('user.show', ['id' => $request->get('id_user')])->withSuccess('Password updated succesfully!');
        }

        return redirect()->route('user.show', ['id' => $request->get('id_user')])->withErrors(['error']);
    }


    /**
     * Update the publishing user permission
     *
     * @return array
     */
    public function updatePermision(Request $request){
        $user = User::where('id',$request->get('user_id'))->first();

        if($user){
            if ($request->get('permission') == 'on'){
                $user->without_validation = 1;
            } else {
                $user->without_validation = 0;
            }
        }

        $user->save();

        return redirect()->back();
    }


    /**
     * Show the email form from an user
     *
     * @return User
     */
    public function email($id)
    {
        $user = User::where('id', $id)->first();
        return view('users.email', ['user' => $user]);
    }


    /**
     * Send and email to the user
     */
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|max:255',
            'body' => 'required|max:255',
        ]);

        if ($validator->passes()) {
            $emails = $request->get('email');
            $subject = $request->get('subject');
            $body = $request->get('body');

            foreach ($emails as $email) {
                $user = User::where('email', $email)->first();
                if ($user){
                    $email_to_user = new Email();
                    $email_to_user->id_user = $user->id;
                    $email_to_user->email = $email;
                    $email_to_user->subject = $subject;
                    $email_to_user->body = $body;
                    $email_to_user->save();

                    Mail::to($email)->send(new EmailUser($email_to_user));
                    return redirect()->back()->withInput()->withErrors(['success']);
                }
            }
        }
    }


    /**
     * Download the information of the users according to the filters applied
     */
    public function download(Request $request){

        if(Auth::guard('web')->check()){

       $ZonesClass = new ZonesClass();

        $array_zones = $ZonesClass->ArrayZones();

        $status = $request->get('status_down');
        $permission = $request->get('permission_down');
        $map = $request->get('map_down');
        $register_date_src = $request->get('register_date_src_down');
        $register_date_dst = $request->get('register_date_dst_down');
        $publish_date_src = $request->get('publish_date_src_down');
        $publish_date_dst = $request->get('publish_date_dst_down');

        if ($register_date_src == null) $register_date_src = '';
        if ($register_date_dst == null) $register_date_dst = '';
        if ($publish_date_src == null) $publish_date_src = '';
        if ($publish_date_dst == null) $publish_date_dst = '';

        $users = User::select('users.*', 'users.id as id_user_real')->status($status)->permission($permission)->map($map)->registered($register_date_src, $register_date_dst)->publishing($publish_date_src, $publish_date_dst)->where('type', 'User')->get();
       $maps = Zone::whereIn('id', $array_zones)->get();

    

        $name_file = 'users';

        } else {

        $status = $request->get('status_down');
        $permission = $request->get('permission_down');
        $map = $request->get('map_down');
        $register_date_src = $request->get('register_date_src_down');
        $register_date_dst = $request->get('register_date_dst_down');
        $publish_date_src = $request->get('publish_date_src_down');
        $publish_date_dst = $request->get('publish_date_dst_down');

        if ($register_date_src == null) $register_date_src = '';
        if ($register_date_dst == null) $register_date_dst = '';
        if ($publish_date_src == null) $publish_date_src = '';
        if ($publish_date_dst == null) $publish_date_dst = '';

        $users = User::select('users.*')->status($status)->permission($permission)->map($map)->registered($register_date_src, $register_date_dst)->publishing($publish_date_src, $publish_date_dst)->get();
         $name_file = 'users';
        }
        if (count($users)) { 
        foreach ($users as $user){

            if($user->active == 1){$user->active = 'Yes';} else {$user->active = 'No';}
            if($user->without_validation == 1){$user->without_validation = 'Yes';} else {$user->without_validation = 'No';}
            if($user->email_verified == 1){$user->email_verified = 'Yes';} else {$user->email_verified = 'No';}

            $data[] = array (
                "ID User"                                   => $user->id,
                "Age"                                   => $user->age,
                "Gender"                                   => $user->gender,
                "Email"                                     => $user->email,
                "Active"                                    => $user->active,
                "Permission to publish without validation"  => $user->without_validation,
                "Verified email"                            => $user->email_verified

            );

        }
        } else {
            $data[] = array (); 
        }
       

        Excel::create($name_file, function($excel) use($data){
            $excel->sheet('Users', function($sheet) use($data){
                $sheet->fromArray( $data );
            });
        })->download('xlsx');
    }
}
