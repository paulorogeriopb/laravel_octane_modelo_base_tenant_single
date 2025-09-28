<?php

declare(strict_types=1);

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Curso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use TenantTrait;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'user_id',     // quem criou
        'tenant_id',   // setado pelo observer
        'updated_by',  // quem atualizou por último
        'image',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
