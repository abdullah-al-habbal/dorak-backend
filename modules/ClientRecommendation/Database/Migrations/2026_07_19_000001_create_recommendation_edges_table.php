<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_edges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->ulidMorphs('source');
            $table->ulidMorphs('target');
            $table->string('edge_type');
            $table->float('weight')->default(0.5);
            $table->json('context')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['source_type', 'source_id', 'edge_type']);
            $table->index(['target_type', 'target_id', 'edge_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_edges');
    }
};
