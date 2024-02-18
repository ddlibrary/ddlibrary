<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use App\Rules\RecaptchaRule;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = DB::table('users')->where('email', $googleUser->email)->first();

        if (! $user) {
            $user = $this->registerUser($googleUser);
        }

        Auth::loginUsingId($user->id);

        return redirect('/');
    }

    private function getUserName($email)
    {
        $username = substr($email, 0, strrpos($email, '@'));
        if (DB::table('users')->where('username', $username)->exists()) {
            return $username.time();
        }

        return $username;
    }

    private function registerUser($data)
    {
        $user = new User();
        $user->username = $this->getUserName($data->email);
        $user->email = $data->email;
        $user->avatar = $data->avatar;
        $user->status = 1;
        $user->accessed_at = Carbon::now();
        $user->language = Config::get('app.locale');
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Create user profile
        $userProfile = new UserProfile();
        $userProfile->user_id = $user->id;
        $userProfile->first_name = $data->name;
        $userProfile->save();

        // Create user role
        $userRole = new UserRole();
        $userRole->user_id = $user->id;
        $userRole->role_id = 6; //library user from roles table
        $userRole->save();

        return $user;
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(): RedirectResponse
    {
        $facebookUser = Socialite::driver('facebook')->user();

        $user = DB::table('users')->where('email', $facebookUser->email)->first();

        if (! $user) {
            $user = $this->registerUser($facebookUser);
        }

        Auth::loginUsingId($user->id);

        return redirect('/');
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

        $credentials = $request->only('user-field', 'password');
        $credentials['user-id'] = null;
        //Checking if user exists
        $userInstance = new User();
        $authUser = $userInstance->oneUser($credentials);

        if (! $authUser) {
            $userProfileInstance = new UserProfile();
            $authUserProfile = $userProfileInstance
                ->getUserProfile($credentials);
            if ($authUserProfile) {
                $credentials['user-id'] = $authUserProfile->user_id;
                $authUser = $userInstance->oneUser($credentials);
            }
        }

        if ($authUser) {
            if (checkUserPassword($credentials['password'], $authUser->password)) {
                $user = new User();
                if ($user->updateUser(['password' => Hash::make($credentials['password'])], $credentials)) {
                    if ($this->attemptLogin($request, $authUser)) {
                        return $this->sendLoginResponse($request);
                    } else {
                        $this->incrementLoginAttempts($request);

                        return $this->sendFailedLoginResponse($request);
                    }
                } else {
                    // If the login attempt was unsuccessful we will increment the number of attempts
                    // to login and redirect the user back to the login form. Of course, when this
                    // user surpasses their maximum number of attempts they will get locked out.
                    $this->incrementLoginAttempts($request);

                    return $this->sendFailedLoginResponse($request);
                }
            } elseif ($this->attemptLogin($request, $authUser)) {
                return $this->sendLoginResponse($request);
            } else {
                // If the login attempt was unsuccessful we will increment the number of attempts
                // to login and redirect the user back to the login form. Of course, when this
                // user surpasses their maximum number of attempts they will get locked out.
                $this->incrementLoginAttempts($request);

                return $this->sendFailedLoginResponse($request);
            }
        } else {
            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
     * Validate the user login request.
     */
    protected function validateLogin(Request $request): void
    {
        $this->validate($request, [
            'user-field' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => [env('CAPTCHA') && env('CAPTCHA') == 'no' ? 'nullable' : 'required', new RecaptchaRule()],
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     */
    protected function credentials(Request $request): array
    {
        $requestArray = $request->only('user-field', 'password');
        if (filter_var($requestArray['user-field'], FILTER_VALIDATE_EMAIL)) {
            $requestArray['email'] = $requestArray['user-field'];
            unset($requestArray['user-field']);

            return $requestArray;
        } else {
            $requestArray['username'] = $requestArray['user-field'];
            unset($requestArray['user-field']);

            return $requestArray;
        }
    }

    /**
     * Attempt to log the user into the application.
     */
    protected function attemptLogin(Request $request, $authUser): bool
    {
        $auth_status = $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
        if (! $auth_status and $authUser) {
            $user_id = $authUser->id;
            $username = $authUser->username;
            $password = $request->only('password');

            return $this->guard()->attempt(
                [
                    'id' => $user_id,
                    'username' => $username,
                    'password' => $password['password'],
                ],
                $request->filled('remember')
            );
        } else {
            return $auth_status;
        }
    }

    public function authenticated(Request $request, $user): RedirectResponse
    {
        $theUser = User::find(Auth::id());
        $theUser->accessed_at = \Carbon\Carbon::now();
        $theUser->save();

        return redirect()->intended('home');
    }

    //Doesn't work here, but for future use
    public function logMeout(Request $request): RedirectResponse
    {
        $this->logout($request);

        return redirect('/home');
    }
}
