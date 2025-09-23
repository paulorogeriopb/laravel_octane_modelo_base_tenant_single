<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audits';

    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relacionamento polimórfico com o modelo auditable
    public function auditable()
    {
        return $this->morphTo();
    }

    // Relacionamento polimórfico com o usuário que fez a ação
    public function user()
    {
        $morphPrefix = config('audit.user.morph_prefix', 'user');

        return $this->morphTo($morphPrefix);
    }
}
