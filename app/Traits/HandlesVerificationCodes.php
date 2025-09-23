<?php

namespace App\Traits;

use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait HandlesVerificationCodes
{
    public function createCode(User $user, string $type, int $minutes = 30): string
    {
        $code = mt_rand(100000, 999999);

        VerificationCode::create([
            'user_id' => $user->id,
            'type' => $type,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes($minutes),
        ]);

        return (string)$code;
    }

    public function validateCode(User $user, string $type, string $code): bool
    {
        $record = VerificationCode::where('user_id', $user->id)
                    ->where('type', $type)
                    ->where('expires_at', '>', now())
                    ->latest()
                    ->first();

        return $record && Hash::check($code, $record->code_hash);
    }
}