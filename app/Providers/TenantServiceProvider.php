<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Scopes\TenantManager;
use App\Scopes\Facade\TenantManagerFacade;
use App\Models\Tenant;
use Session;
use Auth;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
        $url_segments = $this->app->request->segments();
        if(count($url_segments) > 2){

            $tenant_url =  $url_segments[1];

            if($url_segments[0] == "tenant"){

                $tenant = Tenant::where('domain', $tenant_url)->first();

                if($tenant){

                    TenantManagerFacade::set($tenant);

                }else{

                    abort(404, "Tenant Not Found");
                }

            }else{


            }


        }
        

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TenantManager::class, function() {
            return new TenantManager();
        });

        // return TenantManagerFacade::get(); 
    }
}
