<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Newsletter;
use JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;

use Validator, DB, Hash, Mail;

use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class AuthController extends Controller
{
    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $credentials = $request->only('username', 'email', 'password', 'age', 'gender');
        
        $rules = [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required'
        ];
        $messages = [
            'username.required' => 'required',
            'username.max' => 'max',
            'email.required' => 'required',
            'email.unique' => 'unique',
            'email.email' => 'valid',
            'email.max' => 'max',
            'password.required' => 'required'
        ];
        $validator = Validator::make($credentials, $rules, $messages);
        if($validator->fails()) {
            return response()->json(
            [
                'status_code' => 400,
                'data' => [
                    'error'=> json_encode($validator->messages()),
                    'message' => "Validation Fails",
                ]
            ], 400);
        }
        $username = $request->username;
        $lang = $request->lang;
        $age = $request->age;
        $gender = $request->gender;
        $email = $request->email;
        $password = $request->password;
        $user = User::create(['username' => $username, 'age' => $age, 'gender' => $gender, 'email' => $email, 'password' => Hash::make($password), 'active' => 1]);
        $this->addToNewsletter($request);
        $verification_code = str_random(30); //Generate verification code
        DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);
        $subject = "Verify Email - OdourCollect";
		//$from_email = env('MAIL_FROM_ADDRESS');
        $from_email = "odourcollect@ibercivis.es";
        Mail::send('email.verify', ['name' => $username, 'verification_code' => $verification_code , 'lang' => $lang],
            function($mail) use ($email, $username, $subject){
                $mail->from("odourcollect@ibercivis.es", "odourcollect@ibercivis.es");
                $mail->to($email, $username);
                $mail->subject($subject);
            });

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message'=> 'Thanks for signing up! Please check your email to complete your registration.',
            ]
        ], 200);
    }

    /**
     * API Profile Update
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request, $id)
    {

        $verify = 0;
        $credentials = $request->all();
        
        $rules = [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'phone' => 'max:20',
            'datebirth' => 'date',
        ];

        $messages = [
            'name.required' => 'required',
            'name.max' => 'max',
            'surname.required' => 'required',
            'surname.max' => 'max',
            'username.unique' => 'unique',
            'username.max' => 'max',
            'email.required' => 'required',
            'email.unique' => 'unique',
            'email.email' => 'valid',
            'email.max' => 'max',
            'phone.max' => 'max',
            'datebirth.date' => 'date',
        ];

        $validator = Validator::make($credentials, $rules, $messages);
        if($validator->fails()) {
            return response()->json(
            [
                'status_code' => 400,
                'data' => [
                    'error'=> json_encode($validator->messages()),
                    'message' => "Validation Fails",
                ]
            ], 400);
        }

        $user = User::find($id);
        if($user){

            if($user->email != $request->get('email')){ $verify = 1; }

            $user->name = $request->get('name');
            $user->surname = $request->get('surname');
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->phone = $request->get('phone');
            $user->datebirth = $request->get('datebirth');

            $name = $user->name;
            $surname = $user->surname;
            $email = $user->email;

            if($verify){
                $user->email_verified = 0;
                $user->email_verification_date = NULL;
                $verification_code = str_random(30); //Generate verification code
                DB::table('user_verifications')->insert(['user_id' => $user->id, 'token' => $verification_code]);
                $subject = "Verify Email - OdourCollect";
				$from_email = env('MAIL_FROM_ADDRESS');
                Mail::send('email.verify', ['name' => $name.' '.$surname, 'verification_code' => $verification_code],
                    function($mail) use ($email, $name, $surname, $subject){
                        $mail->from($from_email, $from_email);
                        $mail->to($email, $name.' '.$surname);
                        $mail->subject($subject);
                    });
            }

            $user->save();
	    $this->addOrRemoveFromNewsletter($request);

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'message' => "User ".$id." Profile Updated",
                    'email' => $verify,
                    'object' => $user,
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
     * API Profile Delete
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id)
    {

        $error_log("entra en delete de authcontroller - api",0);
        return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'message' => "User ".$id." Profile deleted"
                ]
            ], 200);
    }

    /**
     * API Profile Update
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function password(Request $request, $id)
    {
        $user = User::find($id);
        if($user){

            $credentials = $request->only('email', 'password');

            $credentials['email'] = $user->email;

            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];

            $validator = Validator::make($credentials, $rules);

            if($validator->fails()) {
                return response()->json(
                    [
                        'status_code' => 400,
                        'data' => [
                            'error'=> json_encode($validator->messages()),
                            'message' => "Validation Fails",
                        ]
                    ], 400);
            }


            $user->password = Hash::make( $request->get('new_password') );
           
            $user->save();

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'message' => "User ".$id." Password Updated",
                    'object' => $user,
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
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token',$verification_code)->first();
        if(!is_null($check)){
            $user = User::find($check->user_id);
            if($user->active == 1){
                return view('pages.verified');
            }
            $user->update(['active' => 1]);
            $user->update(['email_verified' => 1]);
            $user->update(['email_verification_date' => date()]);
            DB::table('user_verifications')->where('token',$verification_code)->delete();

            return view('pages.verify'); 
        }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'error'=> 400,
                'message' => "Verification code is invalid",
            ]
        ], 400);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(
            [
                'status_code' => 400,
                'data' => [
                    'error'=> $validator->messages(),
                    'message' => "Validation Fails",
                ]
            ], 400);
        }
        
        $credentials['active'] = 1;
        
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(
                [
                    'status_code' => 401,
                    'data' => [
                        'error' => 401,
                        'message' => 'We cant find an account with this credentials. Please make sure you entered the right information and you have verified your email address.',
                    ]
                ], 401);
            }
        } catch (JWTException $e) {
            var_dump($e);
            // something went wrong whilst attempting to encode the token
            return response()->json(
            [
                'status_code' => 500,
                'data' => [
                    'error'=> 500,
                    'message' => 'Failed to login, please try again.',
                ]
            ], 500);
        }
        $user = User::where( 'email', $request->get('email') )->first();
        $user->count++;
        $user->last_login = now();
        $user->save();
        // all good so return the token
        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message' => 'Successfully Logged In',
                'token' => $token,
                'object' => $user,
            ]
        ], 200);
    }

    /**
     * API Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'message'=> "You have successfully logged out.",
                ]
            ], 200);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(
            [
                'status_code' => 500,
                'data' => [
                    'error'=> 500,
                    'message' => 'Failed to logout, please try again.',
                ]
            ], 500);
        }
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(
            [
                'status_code' => 401,
                'data' => [
                    'errors'=> [
                        'email'=> $error_message
                    ],
                    'message' => 'Validation Fails',
                ]
            ], 401);
        }
        
        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });
        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(
            [
                'status_code' => 401,
                'data' => [
                    'errors'=> $error_message,
                    'message' => 'Validation Fails',
                ]
            ], 401);
        }

        return response()->json(
        [
            'status_code' => 200,
            'data' => [
                'message'=> "A reset email has been sent! Please check your email.",
            ]
        ], 200);
    }

    /**
     * Show the password reset OK dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function passowrdResetOK()
    {
        return view('auth.passwords.resetOK');
    }

    private function addToNewsletter(Request $request){
        $email = $request->get('email');
        $accepted = $request->get('newsletter');

        if($accepted){
            $nn = Newsletter::email($email)->first();
            if(!$nn){
                $newsletter = new Newsletter();
                $newsletter->email = $email;
                $newsletter->accepted = 1;
                $newsletter->save();
            }
        }

        return true;
    }
    private function addOrRemoveFromNewsletter(Request $request){
        $email = $request->get('email');
        $accepted = $request->get('newsletter');

        if($accepted){
            $nn = Newsletter::email($email)->first();
            if(!$nn){
                $newsletter = new Newsletter();
                $newsletter->email = $email;
                $newsletter->accepted = 1;
                $newsletter->save();
            }
        }
	else{
		DB::table('newsletters')
		->where('email', $email)
		->delete();
	}

        return true;
    }


}
