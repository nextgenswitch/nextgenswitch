<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Organization;
use App\Models\ServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\FunctionCall;

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
    protected $redirectTo =   RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function showRegistrationForm(){
        
        if(env('TRIAL_ENABLE')){
            return view('auth.register');
        }
        return redirect()->route('login');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'contact_no' => ['required'],
            'domain' => ['required', 'string', function($attribute, $value, $fail){
                
                $value = sprintf("%s.%s", $value, env('TRIAL_DOMAIN'));

                if(Organization::where('domain', $value)->exists()){
                    $fail('The ' . $attribute . ' has already been taken.');
                }
            }],
        ]);

        
    }  

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        
        if(env('TRIAL_ENABLE')){
            $data['domain'] = sprintf("%s.%s", $data['domain'], env('TRIAL_DOMAIN'));
            $data['address'] = 'ABC Address';

            $organization = Organization::create($data);

            $user = User::create( ['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make( $data['password'] ), 'role' => 'Admin', 'organization_id' => $organization->id] );
            Artisan::call("easypbx:permission admin $user->id");

            $parentOrg = Organization::where('domain', request()->getHost())->first();
            
            $mail = FunctionCall::send_mail([
                'organization_id' => $parentOrg->id,
                'to' =>$organization->email,
                'subject' =>  __("Welcome to NextGenSwitch"),
                'template' => 'welcome',
                'body' => [
                    'name' => $data['name'],
                    'domain' => sprintf('http://%s', $organization->domain),
                    'url' => sprintf('http://%s/login', $organization->domain),
                    'username' => $data['email'],
                    'password' => $data['password'],
                    'sip_server' => $organization->domain,
                    'sip_port' => '5060(TCP/UPD) 5061(TLS)'
                ]
            ]);
            
            return $user;
        }
        
    }

      // Override the default registration method
      public function register(Request $request)
      {
          
          $this->validator($request->all())->validate();
          $user = $this->create($request->all()); 
          
          $user->password = $request->input('password');
          $user->url = sprintf("http://%s/login", $user->organization->domain);
          
          return redirect()->route('reg.info')->with('regUser', $user);
          
      }

      public function regInfo(){
            if(session()->has('regUser')){
                return view('auth.reg_info')->with('user', session('regUser'));
            }

            return redirect()->route('register');
      }
  
      
}
