<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\OperatorPermission as Permission;
use Schema;

class OperatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->registerPolicies();

        if (Schema::hasTable('operator_permissions')) {

            // foreach ($this->getPermissions() as $permission) {

            //     Gate::define($permission->name, function ($user) use ($permission) {

            //         $roles = $permission->roles;
            //         foreach ($roles as $role) {

            //             if ($user->hasRole($role->name)) {
            //                 return true;
            //             }

            //         }

            //         return false;
            //     });

                
            // }
        }

     
    }

    function getPermissions() {

        return Permission::with('roles')->get();
    }

}
