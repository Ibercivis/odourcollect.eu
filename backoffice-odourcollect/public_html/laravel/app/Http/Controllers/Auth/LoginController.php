<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function showLoginForm()
    {
        if(Auth::guard('admin')->check()){
             return redirect()->route('homeadmin');
        }
        
        return view('auth.login');
    }
    public function authenticated(Request $request, $user)
    {
    if ($user->type =='AdminZone') {
        // an admin
        $redirect = '/';
    } else {
        $redirect = '/login';
    }
    return redirect($redirect);
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
