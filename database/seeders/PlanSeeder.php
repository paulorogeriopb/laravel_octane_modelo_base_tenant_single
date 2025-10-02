<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Nim Start',
                'slug' => 'nim-start',
                'stripe_id' => 'price_1SCDhjE23YTKTG0iz2bn5U5x',
                'description' => 'Para quem está começando',
                'active' => true,
                'recommended' => false,
                'price' => 39.00,
                'discount_price' => 59.99, // preço original com risco
                'billing_cycle' => 'monthly',
                'limits' => json_encode([
                    'users' => 2,
                    'properties' => 300,
                    'deals' => 500,
                ]),
                'features' => json_encode([
                    'editor_de_paginas',
                    'page_builder',
                    'integracao_portais_whatsapp',
                ]),
                'trial_days' => 7,
            ],
            [
                'name' => 'Nim Start Anual',
                'slug' => 'nim-start-yearly',
                'stripe_id' => 'price_1SDGnuE23YTKTG0i2x6pKdgP',
                'description' => 'Para quem está começando - Plano anual',
                'active' => true,
                'recommended' => false,
                //'price' => 149.99 * 12 * 0.9, // 10% desconto anual
                'price' => 500.00,
                'discount_price' => null,
                'billing_cycle' => 'yearly',
                'limits' => json_encode([
                    'users' => 2,
                    'properties' => 300,
                    'deals' => 500,
                ]),
                'features' => json_encode([
                    'editor_de_paginas',
                    'page_builder',
                    'integracao_portais_whatsapp',
                ]),
                'trial_days' => 14,
            ],
            [
                'name' => 'Nim Basic',
                'slug' => 'nim-basic',
                'stripe_id' => 'price_1SCDiIE23YTKTG0iyu9jZsRT',
                'description' => 'Para negócios em crescimento',
                'active' => true,
                'recommended' => true,
                'price' => 59.00,
                'discount_price' => 99.99,
                'billing_cycle' => 'monthly',
                'limits' => json_encode([
                    'users' => 5,
                    'properties' => 1000,
                    'deals' => 2000,
                    'contracts' => 50,
                    'emails' => 2000,
                ]),
                'features' => json_encode([
                    'fluxo_caixa',
                    'relatorios',
                    'page_builder',
                    'integracao_whatsapp',
                ]),
                'trial_days' => 14,
            ],
            [
                'name' => 'Nim Basic Anual',
                'slug' => 'nim-basic-yearly',
                'stripe_id' => 'price_1SDHwRE23YTKTG0iy35lh55F',
                'description' => 'Para negócios em crescimento - Plano anual',
                'active' => true,
                'recommended' => true,
                'price' => 800.00,
                'discount_price' => null,
                'billing_cycle' => 'yearly',
                'limits' => json_encode([
                    'users' => 5,
                    'properties' => 1000,
                    'deals' => 2000,
                    'contracts' => 50,
                    'emails' => 2000,
                ]),
                'features' => json_encode([
                    'fluxo_caixa',
                    'relatorios',
                    'page_builder',
                    'integracao_whatsapp',
                ]),
                'trial_days' => 14,
            ],
            [
                'name' => 'Nim Pro',
                'slug' => 'nim-pro',
                'stripe_id' => 'price_1SCDijE23YTKTG0ilTaCcFst',
                'description' => 'Para negócios que escalaram',
                'active' => true,
                'recommended' => false,
                'price' => 99.99,
                'discount_price' => 299.99,
                'billing_cycle' => 'monthly',
                'limits' => json_encode([
                    'users' => 12,
                    'properties' => 'unlimited',
                    'deals' => 5000,
                    'contracts' => 150,
                ]),
                'features' => json_encode([
                    'notificacoes_app',
                    'page_builder',
                    'relatorios_avancados',
                ]),
                'trial_days' => 14,
            ],
            [
                'name' => 'Nim Pro Anual',
                'slug' => 'nim-pro-yearly',
                'stripe_id' => 'price_1SDHxOE23YTKTG0iiPAol7hD',
                'description' => 'Para negócios que escalaram - Plano anual',
                'active' => true,
                'recommended' => false,
                'price' => 2500.00,
                'discount_price' => null,
                'billing_cycle' => 'yearly',
                'limits' => json_encode([
                    'users' => 12,
                    'properties' => 'unlimited',
                    'deals' => 5000,
                    'contracts' => 150,
                ]),
                'features' => json_encode([
                    'notificacoes_app',
                    'page_builder',
                    'relatorios_avancados',
                ]),
                'trial_days' => 14,
            ],
            [
                'name' => 'Nim Ultra',
                'slug' => 'nim-ultra',
                'stripe_id' => '#',
                'description' => 'Para empresas que exigem alto desempenho',
                'active' => true,
                'recommended' => false,
                'price' => 0, // Sob consulta
                'discount_price' => null,
                'billing_cycle' => 'monthly',
                'limits' => json_encode([
                    'users' => 25,
                    'properties' => 'unlimited',
                    'deals' => 'unlimited',
                    'contracts' => 300,
                ]),
                'features' => json_encode([
                    'metricas',
                    'multiplas_filiais',
                    'gestao_comissao',
                    'relatorios_completos',
                ]),
                'trial_days' => 0,
            ],
            [
                'name' => 'Nim Ultra Anual',
                'slug' => 'nim-ultra-yearly',
                'stripe_id' => '#U',
                'description' => 'Para empresas que exigem alto desempenho - Plano anual',
                'active' => true,
                'recommended' => false,
                'price' => 0, // Sob consulta
                'discount_price' => null,
                'billing_cycle' => 'yearly',
                'limits' => json_encode([
                    'users' => 25,
                    'properties' => 'unlimited',
                    'deals' => 'unlimited',
                    'contracts' => 300,
                ]),
                'features' => json_encode([
                    'metricas',
                    'multiplas_filiais',
                    'gestao_comissao',
                    'relatorios_completos',
                ]),
                'trial_days' => 0,
            ],
        ];

        // Cria cada plano no banco
        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}