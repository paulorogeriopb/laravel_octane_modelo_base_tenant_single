<?php

declare(strict_types=1);

namespace App\Models;

use App\Scopes\Tenant\TenantScope;
use Illuminate\Database\Eloquent\Model;
use App\Observers\Tenants\TenantObserver;
use OwenIt\Auditing\Contracts\Auditable;

class Curso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'user_id',
        'tenant_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenantScope());
        static::observe(TenantObserver::class); // <- aqui corrigido
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}