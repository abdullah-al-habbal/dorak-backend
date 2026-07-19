<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_service_history_media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('history_id')->constrained('client_service_histories')->cascadeOnDelete();
            $table->string('photo_url');
            $table->string('photo_type'); // before, after, reference
            $table->timestamp('uploaded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_service_history_media');
    }
};
