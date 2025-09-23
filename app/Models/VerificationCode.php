<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class VerificationCode extends Model
{
    protected $fillable = [
        'user_id', 'code_hash', 'type', 'expires_at'
    ];

    // Garante que expires_at seja convertido para Carbon
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Verifica se o código expirou
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}