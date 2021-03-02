<?php

namespace App\Http\Controllers;

use App\Email;
use App\Http\Controllers\LocationController;
use App\Mail\AdminMail;
use App\Mail\ContactMail;
use App\OdorEmail;
use App\User;
use Illuminate\Http\Request;

use App\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

use App\Services\OdorColor;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


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

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'body' => 'required',
            'user' => 'required',
        ]);

        if ($validator->passes()) {
            $subject = $request->get('subject');
            $body = $request->get('body');
            $user_id = $request->get('user');
            $email = $request->get('email');

            $contact = new Contact();
            $contact->id_user = $user_id;
            $contact->email = $email;
            $contact->subject = $subject;
            $contact->body = $body;
            $contact->save();
			$from_email = env('MAIL_FROM_ADDRESS');

            if ($subject == 'Contact'){
                Mail::to($from_email)->send(new ContactMail($contact));
            } else {
                Mail::to($from_email)->send(new ContactMail($contact));
                
            }


            return response()->json(
                [
                    'status_code' => 200,
                    'data' => [
                        'created' => true,
                        'message' => "Email sent",
                    ]
                ], 200);


        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'created' => false,
                'message' => "There are some errors in the form data.",
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
}
