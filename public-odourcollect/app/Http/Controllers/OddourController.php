<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class OddourController extends Controller
{

    //Route GET /odor/list 
    public function index(Request $request)
    {
        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'odor/list', [
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

    //Route POST /odor/{id}
    public function show($id)
    {
        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'odor/' . $id, [
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



    //Route POST /odor/store
    public function store(Request $request)
    {

        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'odor' , [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);

            $message = json_decode( $result->getBody() );

            return response( json_encode( $message->data ), 200 );

        } catch ( RequestException $e ) {

           $message = json_decode( $e->getResponse()->getBody()->getContents() );

           return response( $message->data , 401 );
        }
    }


    //Route POST /odor/confirm
    public function confirm(Request $request){
        $client = new Client();
        $token = '?token='.$request->get('token');
        $odour = $request->get('id_odor');
        $user = $request->get('id_user');

        try {

            $result = $client->get(env('API_URL') . 'odor/' . $odour . '/confirm/' . $user . $token , [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
            ]);

            $message = json_decode( $result->getBody() );

            return response( json_encode( $message->data ), 200 );

        } catch ( RequestException $e ) {

            $message = json_decode( $e->getResponse()->getBody()->getContents() );

            return response( $message->data->message , 401 );
        }
    }

    //Route POST /odor/update/{id}
    public function update(Request $request, $id)
    {
        $client = new Client();

        try {

            $result = $client->put(env('API_URL') . 'odor/' . $id , [
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

    //Route POST /odor/delete/{id}
    public function delete(Request $request, $id)
    {

        $client = new Client();

        try {

            $result = $client->put(env('API_URL') . 'odor/' . $id . '/delete' ,  [
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

    //Route GET /odor/{odor}/unconfirm/{user}
    public function unconfirm(Request $request, $odor, $user)
    {
        $client = new Client();
        $token = '?token='.$request->get('token');

        try {

            $result = $client->get(env('API_URL') . 'odor/' . $odor . '/unconfirm/' . $user . $token, [
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

    //Route GET /odour/{id}/comment
    public function comments(Request $request, $id){
        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'odor/' . $id . '/comments', [
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

    //Route GET /odor/properties/annoy
    public function annoy()
    {
        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'odor-annoy', [
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

    //Route GET /odor/properties/intensity
    public function intensity()
    {
        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'odor-intensity', [
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

    //Route GET /odor/properties/duration
    public function duration()
    {
        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'odor-duration', [
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

    //Route GET /odor/properties/type
    public function type()
    {
        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'odor-type', [
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

    //Route GET /odor/{odor}/is-confirmed/{id}
    public function isConfirmed(Request $request, $odor, $id )
    {
        $client = new Client();
        $token = '?token='.$request->get('token');

        try {

            $result = $client->get(env('API_URL') . 'odor/' . $odor . '/is-confirmed/' . $id . $token, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ]
            ]);

            $message = json_decode( $result->getBody() );

            return response( json_encode( $result ), 200 );

        } catch ( RequestException $e ) {

            $message = json_decode( $e->getResponse()->getBody()->getContents() );

            return response( $message->data->message , 401 );
        }
    }
}
