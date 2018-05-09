<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //Overwriting the AuthenticatesUsers trait login method
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->only('email', 'password');
        //Checking if user exists
        $userPassword = User::users()->where('email',$credentials['email'])->first();

        if($userPassword){
            if(checkUserPassword($credentials['password'], $userPassword->password)){
                $user = new User();
                if($user->updateUser(array('password' => Hash::make($credentials['password'])), $credentials['email'])){
                    if($this->attemptLogin($request)){
                        return $this->sendLoginResponse($request);
                    }else{
                        $this->incrementLoginAttempts($request);
                        return $this->sendFailedLoginResponse($request);
                    }
                }else{
                    // If the login attempt was unsuccessful we will increment the number of attempts
                    // to login and redirect the user back to the login form. Of course, when this
                    // user surpasses their maximum number of attempts they will get locked out.
                    $this->incrementLoginAttempts($request);
                    return $this->sendFailedLoginResponse($request);
                }
            }else if ($this->attemptLogin($request)){
                return $this->sendLoginResponse($request);     
            }else{
                // If the login attempt was unsuccessful we will increment the number of attempts
                // to login and redirect the user back to the login form. Of course, when this
                // user surpasses their maximum number of attempts they will get locked out.
                $this->incrementLoginAttempts($request);
                return $this->sendFailedLoginResponse($request);
            }
        }
    }

    public function authenticated(Request $request, $user) 
    {
        //return redirect('/home');
    }

    //Doesn't work here, but for future use
    public function logMeout(Request $request)
    {
        $this->logout($request);
        return redirect('/home');
    }
}
