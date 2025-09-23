<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key', 512)->unique(); // Ex: auth.login
            $table->string('group')->nullable(); // Ex: auth, validation, homepage
            $table->json('text'); // JSON com {"pt": "...", "en": "...", "es": "..."}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
