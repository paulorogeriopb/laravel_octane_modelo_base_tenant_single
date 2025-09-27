<?php

namespace App\Tenant;

use Illuminate\Support\Facades\Auth;

class ManagerTenant
{
    public function getTenantIdentify(): ?int
    {
        return Auth::check() ? Auth::user()->tenant_id : null;
    }
}