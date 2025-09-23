<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserStatus extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // Indicar o nome da tabela
    protected $table = 'user_statuses';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = ['name'];

    // usado no select da index user
    public function getColorClassAttribute(): string
    {
        return match ($this->id) {
            1 => 'badge-success',   // Ativo
            2 => 'badge-danger',    // Inativo
            3 => 'badge-info',      // Spam
            4 => 'badge-warning',   // Aguardando Confirmação
            default => 'badge-primary',
        };
    }

    // Criar relacionamento entre um e muitos
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
