<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a criação das tabelas
     */
    public function up(): void
    {
        /**
         * Tabela principal de planos
         * Aqui armazenamos informações básicas do plano, preços, limites e recursos
         */
        Schema::create('plans', function (Blueprint $table) {
            $table->id(); // ID do plano

            // Informações básicas
            $table->string('name')->unique(); // Nome do plano (ex: Nim Start)
            $table->string('slug')->unique(); // Slug para URLs/rotas (ex: nim-start)
            $table->string('stripe_id')->unique()->nullable(); // ID do Stripe, se usado
            $table->text('description')->nullable(); // Descrição do plano
            $table->boolean('active')->default(false); // Plano ativo ou inativo
            $table->boolean('recommended')->default(false); // Destaque como plano recomendado

            // Preço e cobrança
            $table->decimal('price', 12, 2)->default(0.00); // Preço normal do plano
            $table->decimal('discount_price', 12, 2)->nullable(); // Preço promocional opcional

            // Ciclo de cobrança
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            // monthly = mensal, quarterly = trimestral, yearly = anual

            // Limites genéricos (JSON)
            // Exemplo: {"users": 5, "properties": 1000, "deals": 2000}
            $table->json('limits')->nullable();

            // Recursos adicionais (JSON)
            // Exemplo: ["page_builder", "whatsapp_integration", "analytics"]
            $table->json('features')->nullable();

            // Campos extras do sistema (JSON)
            // Pode ser usado para tags, categorias, ou dados customizados
            $table->json('metadata')->nullable();

            // Duração do período de teste (dias)
            $table->integer('trial_days')->default(0);

            $table->timestamps(); // created_at e updated_at
        });

        /**
         * Tabela opcional para features detalhadas
         * Útil se quiser manter uma descrição mais completa de cada recurso do plano
         */
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id(); // ID da feature
            $table->foreignId('plan_id')
                  ->constrained('plans')
                  ->cascadeOnDelete(); // Relacionamento com plano (exclui as features se plano for deletado)
            $table->string('feature'); // Nome da feature (ex: Editor de páginas)
            $table->text('description')->nullable(); // Descrição detalhada da feature
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features'); // Deleta tabela de features primeiro
        Schema::dropIfExists('plans');         // Depois deleta tabela de planos
    }
};