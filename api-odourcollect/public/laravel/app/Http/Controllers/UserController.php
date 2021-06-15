<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Odor;
use App\Location;
use App\User;
use App\Zone;
use App\Point;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user = User::find($id);
	$newsletter = DB::table('newsletters')
	->whereRaw('email="'.$user->email.'"')
	->count();
        if($user){

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'finded' => true,
                    'message' => "User ".$id." has been finded.",
                    'object' => $user,
		    'newsletter' => $newsletter
                ]
            ], 200);

        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'finded' => false,
                'message' => "User not found.",
            ]
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function odours($id)
    {
        $user = User::find($id);
        if($user){

            $odours = Odor::creator($id)->with('likes')->with('comments')->with('location')->verified('1')->where('status', 'published')->get();
            foreach ($odours as $key => $odor) {
                $odor->confirmations = $odor->likes->count();
            }
            
            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'message' => "User Odours finded.",
                    'object' => $odours,
                ]
            ], 200);

        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'finded' => false,
                'message' => "User not found.",
            ]
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zones($id)
    {
        //$user = User::with('zones')->find($id);//->where('user_zones.deleted_at',  NULL)->get(); ->whereNull('user_zones.deleted_at')
        //$user = Zone::where('deleted_at',"NULL")->where('id_user',$id)->get();

        $user_zones = DB::table('zones')
        ->join('user_zones','zones.id','=','id_zone')
        ->where('id_user', '=', $id)
        ->whereNull('user_zones.deleted_at')
        ->get();

        if($user_zones){

            if( count( $user_zones ) > 0){

                
                foreach ($user_zones as $key => $zone) {
                    $points = Point::zone($zone->id_zone)->get();
                    $zone->points = $points;
                }

                return response()->json(
                [
                    'status_code' => 200,
                    'data' => [
                        'message' => "User ".$id." Zones finded.",
                        'object' => $user_zones,
                    ]
                ], 200);
            }else{
                return response()->json(
                [
                    'status_code' => 200,
                    'data' => [
                        'message' => "User ".$id." NO Zones finded.",
                        'object' => null,
                    ]
                ], 200);
            }

        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'finded' => false,
                'message' => "User not found.",
            ]
        ], 400);
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
