<?php


use App\Models\Translation;

if (! function_exists('translation')) {
    function translation(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        $translation = Translation::where('key', $key)->first();

        if (!$translation) {
            return $key;
        }

        return $translation->getTranslation('text', $locale) ?? $key;
    }
}
