<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entity_embeddings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('entity_type');
            $table->uuid('entity_id');
            $table->json('embedding')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('computed_at')->nullable();
            $table->timestamps();

            $table->unique(['entity_type', 'entity_id']);
        });

        try {
            DB::statement('ALTER TABLE entity_embeddings ALTER COLUMN embedding TYPE vector(1536) USING embedding::vector');
        } catch (Throwable) {
            // pgvector not available — json column suffices
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_embeddings');
    }
};
