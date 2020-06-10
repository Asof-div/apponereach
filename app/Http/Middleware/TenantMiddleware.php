<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Tenant;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use Session;
use View;
use Gate;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        if(Auth::guest()){

            $tenant = Tenant::where('domain', $request->domain)->first();
        }
        else{
            if(Session::has('tenant')){

                $tenant = Session::get('tenant');

            }else{

                $tenant = Tenant::where('domain', $request->domain)->first();

            }

        }

        if ( !is_null($tenant) && ($request->domain == $tenant->domain) && $tenant instanceof Tenant && !Auth::check() ) {

            // return dd($request->route()->getPrefix() );
            
            TenantManager::set($tenant);
            app(TenantManager::class);
            // App::set('message', 'EnglishMessage');
            
            View::share('tenant', $tenant);

            return $next($request);
        }elseif( Auth::check() && !is_null($tenant) && $tenant instanceof Tenant ){

            TenantManager::set($tenant);
                // return redirect()->route('tenant.registration.number_selection', [$tenant->domain]);
            if(strtolower($tenant->status) == 'registration' && strtolower($tenant->stage) == 'payment'){

                return redirect()->route('tenant.registration.payment', [$tenant->domain]);
            }elseif(strtolower($tenant->status) == 'registration' && strtolower($tenant->stage) == 'numbering' ){

                return redirect()->route('tenant.registration.number_selection', [$tenant->domain]);
            }elseif( Gate::check('tenant.match') ){
                app(TenantManager::class);
                View::share('tenant', $tenant);
                return $next($request);
            }
            Auth::guard('web')->logout();
            return redirect()->route('login');
        }else{

            abort(404);
        }

        return abort(404);

        
    }
}
