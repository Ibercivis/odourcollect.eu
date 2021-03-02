<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //Route GET /zone/list 
    public function index(Request $request)
    {

        $params = '?';
        foreach ($request->all() as $key => $rq) {
            $params .= $key.'='.$rq.'&';
        }

        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'zone/list' . $params, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ]
            ]);

            $message = json_decode( $result->getBody() );

            return response( json_encode( $message->data ), 200 );

        } catch ( RequestException $e ) {

           $message = json_decode( $e->getResponse()->getBody()->getContents() );

           return response( $message->data->message , 401 );
        }
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
    public function show(Request $request, $id)
    {

        //Get the token
        $token = '?token='.$request->get('token');
        $user = $request->get('user');

        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'zone/' . $id . '/' . $token, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ]
            ]);

            $message = json_decode( $result->getBody() );

            return response( json_encode( $message->data ), 200 );

        } catch ( RequestException $e ) {

           $message = json_decode( $e->getResponse()->getBody()->getContents() );

           return response( $message->data->message , 401 );
        }
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
