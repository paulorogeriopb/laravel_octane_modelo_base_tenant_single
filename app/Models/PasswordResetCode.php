<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetCode extends Model
{
    protected $fillable = ['user_id', 'code_hash', 'expires_at'];
    public $timestamps = true;

    protected $dates = ['expires_at'];

    public function isExpired(): bool
    {
        return $this->expires_at->lt(Carbon::now());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}