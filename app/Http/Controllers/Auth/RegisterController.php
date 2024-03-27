<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\UserProfile;
use App\Rules\RecaptchaRule;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
     * @return Factory|\Illuminate\Contracts\Foundation\Application|View
     */
    public function showRegistrationForm(): View
    {
        $myResources = new Resource();
        $countries = $myResources->resourceAttributesList('taxonomy_term_data', 15);
        $provinces = $myResources->resourceAttributesList('taxonomy_term_data', 12)->all();
        $gmail_signup_url = 'https://accounts.google.com/signup';

        return view('auth.register', compact('countries', 'provinces', 'gmail_signup_url'));
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make(
            $data,
            [
                'email' => 'required_without:phone|string|email|max:255|unique:users|nullable|regex:/^([a-zA-Z\d\._-]+)@(?!fmail.com)/', //Regex to block fmail.com domain
                'password' => 'confirmed|required|string|min:8|regex:/^(?=.*[0-9])(?=.*[!@#$%^&.]).*$/', // Regex for at least one digit and one special character
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required_without:email|max:20|unique:user_profiles|nullable',
                'gender' => 'required',
                'country' => 'required',
                'city' => 'nullable',
                'g-recaptcha-response' => [env('CAPTCHA') && env('CAPTCHA') == 'no' ? 'nullable' : 'required', new RecaptchaRule()],
            ],
            [
                'phone.unique' => __('The phone number has already been taken.'),
                'password.regex' => __('The password you entered doesn\'t have any special characters (!@#$%^&.) and (or) digits (0-9).'),
                'email.regex' => __('Please enter a valid email.'),
            ],
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User $user
     */
    protected function create(Request $request): User
    {
        $user = new User();
        $user->username = $this->getUserName($request['email']);
        $user->password = Hash::make($request['password']);
        $user->email = $request['email'];
        $user->status = 1;
        $user->accessed_at = Carbon::now();
        $user->language = Config::get('app.locale');

        if ($user->email == null) {
            $user->email_verified_at = Carbon::now(); // This is a hack for the duration, until we can verify phone numbers as well
        }

        $user->save();

        return $user;
    }

    private function storeUserProfile($data, $user)
    {
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'country' => $data['country'],
            'city' => $data['city'] ? $data['city'] : ($data['city_other'] ? $data['city_other'] : null),
            'gender' => $data['gender'],
            'phone' => $data['phone'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     *
     * @return Application|RedirectResponse|Redirector
     *
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        try {
            DB::beginTransaction();

            // Create user
            $user = $this->create($request);

            // Send email verification
            if (env('SEND_EMAIL') && env('SEND_EMAIL') != 'no') {
                if ($user->email) {
                    event(new Registered($user));
                }
            } else {
                $user->email_verified_at = Carbon::now();
                $user->save();
            }

            // Add user profile
            $this->storeUserProfile($request, $user);

            // Assign role to user
            $user->roles()->attach(6); //6 is library user from roles table

            // Subscribe
            /* TODO: Insert name and user_id into the table as well */
            /* TODO: Verify emails before inserting them into the table - perhaps this isn't the best place for that */
            /*
            if($user->email && $request->subscribe){
                Subscriber::create(['email' => $user->email]);
            }
            */

            DB::commit();

            Auth::loginUsingId($user->id);

            if ($user->email) {
                return redirect('email/verify');
            }

            return $this->registered($request, $user->id) ?: redirect($this->redirectPath());
        } catch (Exception $e) {
            DB::rollback();
        }

        return back()->with('error', 'Sorry! Your account has not been created.');
    }

    private function getUserName($email)
    {
        $username = substr($email, 0, strrpos($email, '@'));

        if (DB::table('users')->where('username', $username)->exists()) {
            return $username.time();
        }

        return $username;
    }
}
