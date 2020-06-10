<?php

namespace App\Http\Controllers\App\Operator\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
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

    protected $guard = 'operator';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/operator/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:operator', ['except' => ['logout']]);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.operator.login');
    }

/**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('operator');
    }

    public function logout(Request $request)
    {
        
        Auth::guard('operator')->logout();

        $request->session()->flush();

        $request->session()->regenerate();
        
        return redirect(route( 'operator.login' ));
    }


}
