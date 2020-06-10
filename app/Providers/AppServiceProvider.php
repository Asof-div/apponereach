<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        /*if(env('REDIRECT_HTTPS'))
        {
            $url->forceSchema('https');
        }*/

        if (config('app.env', 'prod') == 'prod' || config('app.env', 'production') == 'production')
        {
            \URL::forceScheme('https');
        }

        Validator::extend('phone_number', function ($attribute, $value) {
            if(!is_numeric($value)){
                return false;
            }

            return preg_match('%^(07)|(08)|(09)[0-9]{9}$%', $value) && strlen($value) == 11;
        });

        Validator::replacer('phone_number', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, ':attribute is an invalid phone number. Must begin with either 07XXXXXXXXX, 08XXXXXXXXX, 09XXXXXXXXX');
        });

        Passport::tokensExpireIn(now()->addDays(15));

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
