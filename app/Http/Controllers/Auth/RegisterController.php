<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use BladeView;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Resource;
use App\UserProfile;
use App\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return BladeView|bool|Factory|Application|View
     */
    public function showRegistrationForm()
    {
        $myResources = new Resource();
        $countries = $myResources->resourceAttributesList('taxonomy_term_data',15);
        $provinces = $myResources->resourceAttributesList('taxonomy_term_data',12)->all();
        $gmail_signup_url = 'https://accounts.google.com/signup';
        return view('auth.register', compact('countries', 'provinces', 'gmail_signup_url'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'email' => 'required_without:phone|string|email|max:255|unique:users|nullable|regex:/^([a-zA-Z\d\._-]+)@(?!fmail.com)/', //Regex to block fmail.com domain
                'username' => 'required|string|max:255',
                'password' => 'confirmed|required|string|min:8|regex:/^(?=.*[0-9])(?=.*[!@#$%^&.]).*$/',  // Regex for at least one digit and one special character
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required_without:email|max:20|unique:user_profiles|nullable',
                'gender' => 'required',
                'country' => 'required',
                'city' => 'nullable',
                'g-recaptcha-response' => 'required|captcha'
            ],
            $messages = [
                'phone.unique' => __('The phone number has already been taken.'),
                'password.regex' => __('The password you entered doesn\'t have any special characters (!@#$%^&.) and (or) digits (0-9).'),
                'email.regex' => __('Please enter a valid email.')
            ]
        );
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return array
     */
    protected function create($data)
    {
        $user = new User();
        $user->username = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->status = 1;
        $user->accessed_at = Carbon::now();
        $user->language = Config::get('app.locale');

        $using_email = True;
        if ($user->email == null) {
            $user->email_verified_at = Carbon::now(); // This is a hack for the duration, until we can verify phone numbers as well
            $using_email = False;
        }

        $user->save();

        if(isset($data['city'])){
            $city = $data['city'];
        }elseif(isset($data['city_other'])){
            $city = $data['city_other'];
        }else{
            $city = NULL;
        }

        $userProfile = new UserProfile();
        $userProfile->user_id       = $user->id;
        $userProfile->first_name    = $data['first_name'];
        $userProfile->last_name     = $data['last_name'];
        $userProfile->country       = $data['country'];
        $userProfile->city          = $city;
        $userProfile->gender        = $data['gender'];
        $userProfile->phone         = $data['phone'];
        $userProfile->save();

        $userRole = new UserRole;
        $userRole->user_id = $user->id;
        $userRole->role_id = 6; //library user from roles table
        $userRole->save();

        return array($user->id, $using_email);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        list($userId, $using_email) = $this->create($request->all());

        Auth::loginUsingId($userId);

        if ($using_email)
            return redirect('email/verify');

        return $this->registered($request, $userId)
                        ?: redirect($this->redirectPath());
    }
}

