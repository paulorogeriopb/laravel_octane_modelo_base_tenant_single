<?php

namespace App\Tenant;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class ManagerTenant
{
    public function getTenantIdentify(): ?int
    {
        return Auth::check() ? Auth::user()->tenant_id : null;
    }


    public function getTenant(): Tenant
    {
        return Auth::check() ? Auth::user()->tenant : null;
    }


}