<?php

namespace App\Http\Controllers\Auth;

use App\Models\UserReg;
use App\Models\Tenant;
use App\Models\Package;
use App\Models\Addon;
use App\Models\TenantInfo;
use App\Models\Operator\PilotNumber;
use App\Models\PilotLine;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Services\PilotDefaultDestination as PilotDefault;
use App\Services\Tenant\RegistrationService;
use App\Helpers\PlanHelper;

use App\Events\UserWasRegistered;
use DB;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->registration = new RegistrationService();
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
            'corporation_name' => 'required|string|max:255',
            'corporation_short_name' => 'required|unique:tenants,domain|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mobile_no' => 'required|numeric|phone_number|digits:11',
            'email' => 'required|string|email|max:255',
            'plan' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        
        $user = $this->registration->registerTenant($data);
        
        $redirectTo = $user->tenant->domain;
        
        return $user;
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request, PlanHelper $planHelper)
    {
        // return view('mails.new_user');
        $packages = Package::where('name', '<>', 'Free')->where('price', '<>', 0)->orderBy('price')->with(['addons'])->get();
        $addons = Addon::orderBy('binary_index')->get();
        $plan = $planHelper;
        $tenant = new Tenant;
        if($request->has('plan') && count(explode('_', $request->plan)) > 1 ){
            $planHelper->setPlan($request->plan);
            $planHelper->setStage(2);
            $plan = $planHelper;
            return redirect()->route('register.customer', [$request->plan]);
        }

        // return redirect()->route('login')->with('flash_message', 'Pre-Pay option is not available yet. To sign simple branch any 9mobile experience centre.');
        return view('auth.signup.register', compact('packages', 'addons', 'plan'));
    }


      /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        
        $validator = $this->validator($request->all());
        $validator->validate();
        try{

            DB::beginTransaction();

            $user = $this->create($request->all());

            $this->guard()->login($user);
            event(new UserWasRegistered($user, ''));

            DB::commit();

        }catch(\Exception $e){

            \Log::info($e );
            DB::rollback();
            $validator->errors()->add('plan', $e->getMessage() );
            return redirect()->back()->withInput()->withErrors($validator);

        }

        // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }


    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        

        return redirect()->route('tenant.registration.number_selection', [$user->tenant->domain]);
    }

    public function customerForm(Request $request, PlanHelper $planHelper, $planr){
        $validator = validator($request->all(), []);
        $addons = Addon::orderBy('binary_index')->get();
        $plan = $planHelper;
        if($planr && count(explode('_', $planr)) > 1 ){
            $planHelper->setPlan($planr);
            $planHelper->setStage(2);
            $plan = $planHelper;
            if(is_null($plan->getPackage()) ){
                $validator->errors()->add('plan', 'Invalid plan selected');
                return redirect()->route('register')->withErrors($validator);
            }
        }


        return view('auth.signup.customer', compact('packages', 'addons', 'plan'));
    }

    private function processDomain($domain){
        $domain = strtolower($domain);
        $domain = str_replace(" ", "_", $domain);
        $domain = str_replace("/", "_", $domain);
        $domain = str_replace("\\", "_", $domain);
        $domain = str_replace("/", "_", $domain);

        return $domain;
    }

}
