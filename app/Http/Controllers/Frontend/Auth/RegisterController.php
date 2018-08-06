<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use App\Models\UserProfile;
use Validator;
use App\Http\Controllers\Frontend\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Helpers\OutWorldAuth;
use App\Models\Role;

class RegisterController extends Controller
{
    use RegistersUsers;

    use OutWorldAuth {
        OutWorldAuth::__construct as private __owaConstruct;
    }
    
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/settings';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
        $this->__owaConstruct();
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm() {
       if ( ! session_id()) session_start();
        
        return view('frontend.auth.register', [
            'fbLoginUrl'     => $this->facebookAuthUrl(),
            'googleLoginUrl' => $this->googleAuthUrl(),
        ]);
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, $this->getRegisterRules(), $this->getRegisterMessages());
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        
        $validator = $this->validator($request->all());
        
        if ($validator->fails()) {
            return redirect(route('front_register'))->withErrors($validator)->withInput();
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
    
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * 
     * @return User
     */
    protected function create(array $data) {
        
        $roleMember = Role::where('slug', 'member')->first();
        $user       = User::create([
            'email'    => $data['email'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'role_id'  => ($roleMember) ? $roleMember->id : 2
        ]);
        
        $userProfile          = new UserProfile();
        $userProfile->user_id = $user->id;
        $userProfile->slug    = $data['username'];
        $userProfile->save();
        
        return $user;
    }
    
    /**
     * Get register validation rules
     *
     * @return type
     */
    public function getRegisterRules() {
        return [
            'email'    => 'required|email|max:128|unique:users,email',
            'username' => 'required|min:6:|max:64|alpha_dash|unique:users,username',
            'password' => 'required|min:6|max:60',
        ];
    }
    
    /**
     * Get register validation messages
     * 
     * @return array
     */
    public function getRegisterMessages() {
        return [
            'email.required'      => _t('register.email.req'),
            'email.email'         => _t('register.email.email'),
            'email.max'           => _t('register.email.max'),
            'email.unique'        => _t('register.email.uni'),
            'username.required'   => _t('register.uname.req'),
            'username.min'        => _t('register.uname.min'),
            'username.max'        => _t('register.uname.max'),
            'username.alpha_dash' => _t('register.uname.aldash'),
            'username.unique'     => _t('register.uname.uni'),
            'password.required'   => _t('register.pass.req'),
            'password.min'        => _t('register.pass.min'),
            'password.max'        => _t('register.pass.max'),
        ];
    }
}
