<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Tenant\ManagerTenant;

class UploadService
{
    protected string $disk;

    public function __construct(string $disk = 'tenant')
    {
        $this->disk = $disk;
    }

    /**
     * Faz o upload do arquivo e retorna o path relativo salvo no storage
     */
    public function upload(UploadedFile $file, string $name = null): string
    {
        // Tenant
        $tenant = app(ManagerTenant::class)->getTenant();
        $tenantFolder = $tenant->uuid;

        // Estrutura WordPress-like (Y/m/d)
        $datePath = now()->format('Y/m/d');
        $fullPath = "{$tenantFolder}/{$datePath}";

        // Cria a pasta caso não exista
        if (!Storage::disk($this->disk)->exists($fullPath)) {
            Storage::disk($this->disk)->makeDirectory($fullPath, 0755, true);
        }

        // Nome do arquivo
        $extension = $file->extension();
        $fileName = ($name ? Str::kebab($name) : Str::random(16)) . '-' . time() . '.' . $extension;

        // Salva o arquivo
        $file->storeAs($fullPath, $fileName, $this->disk);

        return "{$fullPath}/{$fileName}";
    }

    /**
     * Deleta um arquivo do storage
     */
    public function delete(string $path): bool
    {
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }
        return false;
    }
}