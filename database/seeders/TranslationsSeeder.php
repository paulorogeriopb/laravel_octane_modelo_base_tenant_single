<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $langPath = base_path('lang');
        if (! is_dir($langPath)) {
            $this->command->warn('❌ Pasta lang/ não encontrada.');
            return;
        }

        $locales = array_filter(scandir($langPath), fn ($dir) => is_dir("$langPath/$dir") && !in_array($dir, ['.', '..']));
        $files = File::files($langPath); // Para os JSONs na raiz

        $translations = [];

        // 🟩 Arquivos por idioma (auth.php, validation.php etc.)
        foreach ($locales as $locale) {
            $localeFiles = File::allFiles("$langPath/$locale");

            foreach ($localeFiles as $file) {
                $group = $file->getFilenameWithoutExtension();
                $lines = include $file->getPathname();

                if (!is_array($lines)) {
                    continue;
                }

                foreach ($this->arrayDot($lines) as $key => $value) {
                    $fullKey = "$group.$key";

                    if (isset($translations[$fullKey]['text'][$locale]) && $translations[$fullKey]['text'][$locale] !== $value) {
                        $this->command->warn("⚠️ Chave duplicada com valores diferentes: $fullKey ($locale)");
                    }

                    $translations[$fullKey]['key'] = $fullKey;
                    $translations[$fullKey]['group'] = $group;
                    $translations[$fullKey]['text'][$locale] = $value;
                }
            }
        }

        // 🟨 Arquivos JSON de idioma (pt_BR.json, en.json)
        foreach ($files as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $locale = $file->getFilenameWithoutExtension();
            $lines = json_decode($file->getContents(), true);

            if (!is_array($lines)) {
                continue;
            }

            foreach ($lines as $key => $value) {
                $group = 'json'; // ou null

                if (isset($translations[$key]['text'][$locale]) && $translations[$key]['text'][$locale] !== $value) {
                    $this->command->warn("⚠️ Chave duplicada com valores diferentes no JSON: $key ($locale)");
                }

                $translations[$key]['key'] = $key;
                $translations[$key]['group'] = $group;
                $translations[$key]['text'][$locale] = $value;
            }
        }

        // 🧩 Salva no banco
        foreach ($translations as $item) {
            Translation::updateOrCreate(
                ['key' => $item['key']],
                [
                    'group' => $item['group'],
                    'text' => $item['text'],
                ]
            );
        }

        $this->command->info('✅ Traduções importadas com sucesso do diretório lang/.');
    }

    // 📦 Equivalente ao array_dot
    protected function arrayDot(array $array, string $prepend = ''): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results += $this->arrayDot($value, $prepend . $key . '.');
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }
}
