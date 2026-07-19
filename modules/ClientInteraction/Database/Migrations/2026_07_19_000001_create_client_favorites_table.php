<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_favorites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('clients')->cascadeOnDelete();
            $table->uuidMorphs('favorable');
            $table->timestamps();

            $table->unique(['client_id', 'favorable_id', 'favorable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_favorites');
    }
};
