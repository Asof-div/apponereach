<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Repos\Facade\TenantGateValidatorFacade as TenantGate;
use Auth;
use App\Models\Permission;
use App\Models\AdminPermission;
use App\Models\OperatorPermission;
use Schema;
use App\Models\Admin;
use App\Models\Operator;
use App\Models\User;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\Addon;
use App\Models\Package;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        
        Gate::define('domain.setting', function ($user){

            $tenant = TenantManager::get();
            TenantGate::set($tenant, $user);
            
            return TenantGate::manager();
        });

        Gate::define('tenant.match', function ($user){

            $tenant = TenantManager::get();
            TenantGate::set($tenant, $user);
            
            return TenantGate::match();
        });

        Gate::define('tenant.subscription', function ($user){

            $tenant = TenantManager::get();
            TenantGate::set($tenant, $user);
            
            return TenantGate::subscription($tenant);
        });

        Gate::define('tenant.expiring', function ($user){

            $tenant = TenantManager::get();
            TenantGate::set($tenant, $user);
            
            return TenantGate::expiring($tenant);
        });

        Gate::define('owns.ticket', function ($user, $ticket){
            
            if ( $user instanceof Operator ) {
            
                if($ticket->assigned_operator_id == $user->id){
                    return true;
                }

                if($ticket->creator_id == $user->id && $ticket->creator_type == 'App\Models\Operator'){
                    return true;
                }

                if($user->sadmin == true){
                    return true;
                }

            } elseif ( $user instanceof User ) {

                if($ticket->creator_id == $user->id && $ticket->creator_type == 'App\Models\User'){
                    return true;
                }

                if($user->manager == true){
                    return true;
                }

            } elseif ( $user instanceof Admin ) {
                
                if($ticket->assigned_admin_id == $user->id){
                    return true;
                }

                if($ticket->creator_id == $user->id && $ticket->creator_type == 'App\Models\Admin'){
                    return true;
                }

                if($user->sadmin == true){
                    return true;
                }
            } 
            
            return false;
        });


        Gate::define('owns.incident', function ($user, $incident){
            
            if( is_null($incident) ){ return false; }

            if ( $user instanceof Operator ) {
            
                if($incident->operator_id == $user->id){
                    return true;
                }

                if($user->sadmin == true){
                    return true;
                }

            } elseif ( $user instanceof Admin ) {
                
                if($incident->admin_id == $user->id){
                    return true;
                }

                if($user->sadmin == true){
                    return true;
                }
            } 
            
            return false;
        });


        if (Schema::hasTable('operator_permissions')) {

            foreach ($this->getOperatorPermissions() as $Operatorpermission) {               
                Gate::define($Operatorpermission->name, function (Operator $user) use ($Operatorpermission) {
                    $guard = Auth::guard('operator')->check('operator');
                    // dd( $guard );
                    
                    $roles = $Operatorpermission->roles;
                    foreach ($roles as $role) {

                        if ($user->hasRole($role->name)) {
                            return true;
                        }
                        
                    }
                    if($user->sadmin == true){
                        return true;
                    }

                    return false;
                });

                
            }

        }

        $url_segments = $this->app->request->segments();
        if(count($url_segments) > 2){

            $tenant_url =  $url_segments[1];

            if($url_segments[0] == "tenant"){

                $tenant = Tenant::where('domain', $tenant_url)->first();

                if($tenant){

                    if (Schema::hasTable('permissions')) {

                        foreach ($this->getUserPermissions() as $permission) {               
                            Gate::define($permission->name, function ($user) use ($permission, $tenant) {
                                                            
                                $roles = $permission->roles->filter(function($query) use ($tenant){

                                        return $query->tenant_id == $tenant->id || $query->system ==  true;
                                    });
                                foreach ($roles as $role) {

                                    if ($user->hasRole($role->name)) {
                                        return true;
                                    }
                                    
                                }
                                if($user->manager == true){
                                    return true;
                                }

                                return false;
                            });

                            
                        }
                    }
                }
            }
        }


        if(Schema::hasTable('addons') && Schema::hasTable('packages')){

            foreach (Addon::with('packages')->get() as $addon) {               
                Gate::define($addon->permission_label, function (User $user) use ($addon) {
                    
                    $packages = $addon->packages;
                    foreach ($packages as $package) {

                        if ($user->hasPackage($package->id)) {
                            return true;
                        }
                        
                    }

                    return false;
                });

                
            }
        }

        Gate::define('cart',function(User $user){
            if( strtolower($user->tenant->billing_method) == 'prepay' && $user->tenant->status()){

                return true;
            }
            return false;
        });



    }


    function getAdminPermissions() {

        return AdminPermission::with('roles')->get();
    }


    function getOperatorPermissions() {

        return OperatorPermission::with('roles')->get();
    }


    function getUserPermissions() {

        return Permission::get();
    }

}
