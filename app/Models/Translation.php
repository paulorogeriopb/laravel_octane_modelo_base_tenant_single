<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Translation extends Model
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'group',
        'text',
    ];

    public $translatable = ['text'];
}
