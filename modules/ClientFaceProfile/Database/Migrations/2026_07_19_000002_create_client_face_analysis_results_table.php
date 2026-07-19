<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_face_analysis_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignUuid('face_profile_id')->nullable()->constrained('client_face_profiles')->nullOnDelete();
            $table->string('analysis_version');
            $table->string('analysis_source');
            $table->string('detected_face_shape');
            $table->decimal('confidence_score', 4, 2);
            $table->json('detected_features')->nullable();
            $table->json('recommended_catalog_item_ids')->nullable();
            $table->timestamp('computed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_face_analysis_results');
    }
};
