<?php

namespace App\Observers\Tenants;

use App\Tenant\ManagerTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TenantObserver
{
    public function creating(Model $model): void
    {
        // Define tenant_id automaticamente
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        if ($tenant) {
            $model->setAttribute('tenant_id', $tenant);
        }

        // Define user_id automaticamente
        if (Auth::check()) {
            $model->setAttribute('user_id', Auth::id());
        }
    }
}
