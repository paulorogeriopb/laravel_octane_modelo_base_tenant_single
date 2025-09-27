<?php

namespace App\Tenant\Traits;
use App\Scopes\Tenant\TenantScope;
use App\Observers\Tenants\TenantObserver;

trait TenantTrait
{
     protected static function boot()
     {
        parent::boot();

        static::addGlobalScope(new TenantScope());
        static::observe(TenantObserver::class);
     }
}
