<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Models\Role;
use App\Models\AdminRole;
use App\Models\OperatorRole;
use App\Models\Incident;
use App\Models\Ticket;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Billing;
use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Models\Operator;
use App\Models\Admin;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        // Route::model('operator_role', OperatorRole::class);
        Route::bind('operator_role', function ($value) {
            return OperatorRole::find($value) ?? abort(404);
        });
        Route::bind('user_role', function ($value) {
            return Role::find($value) ?? abort(404);
        });
        Route::bind('incident_id', function ($value) {
            return Incident::find($value) ?? abort(404);
        });
        Route::bind('ticket_id', function ($value) {
            return Ticket::find($value) ?? abort(404);
        });
        Route::bind('tenant_id', function ($value) {
            return Tenant::find($value) ?? abort(404);
        });
        Route::bind('user_id', function ($value) {
            return User::find($value) ?? abort(404);
        });
        Route::bind('billing_id', function ($value) {
            return Billing::find($value) ?? abort(404);
        });
        Route::bind('subscription_id', function ($value) {
            return Subscription::find($value) ?? abort(404);
        });
        Route::bind('payment_id', function ($value) {
            return PaymentTransaction::find($value) ?? abort(404);
        });
        Route::bind('operator_id', function ($value) {
            return Operator::find($value) ?? abort(404);
        });
        Route::bind('admin_id', function ($value) {
            return Admin::find($value) ?? abort(404);
        });

    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
