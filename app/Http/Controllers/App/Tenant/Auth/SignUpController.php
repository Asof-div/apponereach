<?php

namespace App\Http\Controllers\App\Tenant\Auth;

use App\Models\UserReg;
use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class SignUpController extends Controller
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
    // protected $redirectTo = '/home';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        // return dd(explode('@', 'abiodun.adeyinka@telvida.com'));
        return view('auth.register');
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        return dd($data);
        
        $tenant = $this->regTenant($data);

        
    }


    public function regTenant($data){

        $tenant = new Tenant;
        $tenant->name = $data['name'];
        $tenant->domain = $data['domain'];
        $tenant->tenant_no = 'TEN'.sprintf("%09d", $tenant->id);

        $tenant->save();

        $user = UserReg::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'tenant_id' => $tenant->id,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $tenant->info()->save( new TenantInfo( array('email' => $data['email'], 'updated_by' => $user->id )));

        return $user;

    }


}
