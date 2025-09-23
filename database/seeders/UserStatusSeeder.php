<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\UserStatus;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'name' => 'Ativo'],
            ['id' => 2, 'name' => 'Inativo'],
            ['id' => 3, 'name' => 'Aguardando Confirmação'],
            ['id' => 4, 'name' => 'Spam'],
        ];

        foreach ($statuses as $status) {
            try {
                UserStatus::updateOrCreate(
                    ['id' => $status['id']],      // busca pelo ID
                    ['name' => $status['name']]   // dados para atualizar/criar
                );
            } catch (Exception $e) {
                Log::notice('Erro ao cadastrar status do usuário.', [
                    'status' => $status,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
