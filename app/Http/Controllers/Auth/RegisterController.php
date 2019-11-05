<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Resource;
use App\UserProfile;
use App\UserRole;
use Illuminate\Support\Facades\Hash;
use Config;

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
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $myResources = new Resource();
        $countries = $myResources->resourceAttributesList('taxonomy_term_data',15);
        $provinces = $myResources->resourceAttributesList('taxonomy_term_data',12)->all();
        return view('auth.register', compact('countries','provinces'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|max:20',
            'gender' => 'required',
            'country' => 'required',
            'city' => 'nullable',
            'g-recaptcha-response' => 'sometimes|required|captcha'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create($data)
    {
        $user = new User();
        $user->username = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->status = 1;
        $user->accessed_at = \Carbon\Carbon::now();
        $user->language = Config::get('app.locale');
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

        return $user->id;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $userId = $this->create($request->all());

        Auth::loginUsingId($userId);

        return $this->registered($request, $userId)
                        ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $userId)
    {
        
    }
}
