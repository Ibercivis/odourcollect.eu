<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller {
    public function login(Request $request) {

        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'login', [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);

            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {

            $message = json_decode($e->getResponse()->getBody()->getContents());

            return response($message->data->message, 401);
        }

    }

    public function recover (Request $request){
        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'recover', [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);

            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {

            $message = json_decode($e->getResponse()->getBody()->getContents());

            print_r($message->data);

            //return response($message->data->message, 401);
        }
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 403);
        }

        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'register', [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);
          
            print_r($result->getBody());
            
            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {
          
            print_r($e->getResponse()->getBody()->getContents());
            $message = json_decode($e->getResponse()->getBody()->getContents());

            return response($message, 401);
        }

    }

    public function show(Request $request, $id) {

        //Get the token
        $token = '?token=' . $request->get('token');

        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'user/' . $id . $token, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
            ]);

            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {

            $message = json_decode($e->getResponse()->getBody()->getContents());

            return response($message->data->message, 401);
        }

    }

    public function storeComment(Request $request){

        $client = new Client();
        $token = '?token='.$request->get('token');
        $odour = '&id_odor='.$request->get('id_odor');
        $user = '&id_user='.$request->get('id_user');
        $comment = '&comment='.$request->get('comment');

        try {

            $result = $client->post(env('API_URL') . 'comment/store' . $token . $odour . $user . $comment, [
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

    public function odours(Request $request, $id)
    {
        //Get the token
        $token = '?token=' . $request->get('token');

        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'user/' . $id . '/odours' . $token, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
            ]);

            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {

            $message = json_decode($e->getResponse()->getBody()->getContents());

            return response($message->data->message, 401);
        }

    }

    public function zones(Request $request, $id) {

        //Get the token
        $token = '?token=' . $request->get('token');

        $client = new Client();

        try {

            $result = $client->get(env('API_URL') . 'user/' . $id . '/zones' . $token, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
            ]);

            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {

            $message = json_decode($e->getResponse()->getBody()->getContents());

            return response($message->data->message, 401);
        }

    }

    //Recupera la informacio d'un usuari
    public function profile($id, $token) {
        $client = new Client();
        $result = $client->get(env('API_URL') . 'user/' . $id . '?token=' . $token, [
            'headers' => [
                'api-key' => env('API_KEY'),
            ],
        ]);

        return json_encode(json_decode($result->getBody()));
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'max:20',
            'datebirth' => 'date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 403);
        }

        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'user/' . $id, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);

            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {

            $message = json_decode($e->getResponse()->getBody()->getContents());

            return response($message->data->error, 401);
        }

    }


    public function delete(Request $request, $id) {
        
        $client = new Client();
        $token = '?token=' . $request->get('token');

        try {  
                     
            $result = $client->put(env('API_URL') . 'user/' . $id . '/delete' , [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);
            
/*
            $result = $client->get(env('API_URL') . 'user/' . $id . '/zones' . $token, [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
            ]);
*/
            print_r("llamando");
            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {            
            $message = json_decode($e->getResponse()->getBody()->getContents());
            print_r($message);
            return response("errrror", 401);
        }

    }


    public function password(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'password' => 'required_with:rep_password|same:rep_password',
            'rep_password' => 'required'
        ]);

        

        $client = new Client();

        try {

            $result = $client->post(env('API_URL') . 'user/' . $id  . '/password', [
                'headers' => [
                    'api-key' => env('API_KEY'),
                ],
                'form_params' => $request->all(),
            ]);

            $message = json_decode($result->getBody());

            return response(json_encode($message->data), 200);

        } catch (RequestException $e) {

            $message = json_decode($e->getResponse()->getBody()->getContents());

            return response($message->data->error, 401);
        }

    }

    public function sendContact(Request $request){

        $client = new Client();
        $token = '?token='.$request->get('token');
        $subject = '&subject='.$request->get('subject');
        $user = '&user='.$request->get('user');
        $body = '&body='.$request->get('body');
        $email = '&email='.$request->get('email');

        try {

            $result = $client->get(env('API_URL') . '/contact/' . $token . $user . $subject . $body . $email, [
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

}
