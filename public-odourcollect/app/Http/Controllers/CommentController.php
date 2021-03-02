<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class CommentController extends Controller
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

    //Route POST /comment/store
    public function store(Request $request)
    {
        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'comment/store' , [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);

            $message = json_decode( $result->getBody() );

            return response( json_encode( $message->data ), 200 );

        } catch ( RequestException $e ) {

           $message = json_decode( $e->getResponse()->getBody()->getContents() );

           return response( $message->data->message , 401 );
        }
    }

    public function hide(Request $request, $id)
    {
        $client = new Client();

        try {

            $result = $client->put(env('API_URL') . 'comment/' . $id . '/hide' , [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);

            $message = json_decode( $result->getBody() );

            return response( json_encode( $message->data ), 200 );

        } catch ( RequestException $e ) {

            $message = json_decode( $e->getResponse()->getBody()->getContents() );

            return response( $message->data->message , 401 );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
