<?php

// modules/Preference/Database/Migrations/0001_01_01_000001_create_preferences_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('preferenceable_id')->nullable();
            $table->string('preferenceable_type')->nullable();
            $table->string('preferred_language', 10)->nullable();
            $table->boolean('notification_enabled')->default(true);
            $table->foreignUuid('display_currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->string('theme')->nullable();
            $table->string('price_display_mode')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
