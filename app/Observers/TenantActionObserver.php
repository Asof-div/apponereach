<?php

namespace App\Observers;
use App\Models\Tenant;

class TenantActionObserver
{

    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(Tenant $tenant)
    {
        $tenant->code = sprintf("%05X", $tenant->id);
        $tenant->tenant_no = 'COM_'.sprintf("%05X", $tenant->id);
        $tenant->update();
    }


}
