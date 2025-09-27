<?php

namespace App\Rules\Tenant;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Tenant\ManagerTenant;
use Illuminate\Support\Facades\DB;

class TenantUnique implements ValidationRule
{
    private string $table;
    private ?int $ignoreId;

    public function __construct(string $table, ?int $ignoreId = null)
    {
        $this->table = $table;
        $this->ignoreId = $ignoreId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tenant = app(ManagerTenant::class)->getTenantIdentify();

        $query = DB::table($this->table)
            ->where($attribute, $value)
            ->where('tenant_id', $tenant);

        if ($this->ignoreId) {
            $query->where('id', '<>', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail("O {$attribute} já está em uso.");
        }
    }
}