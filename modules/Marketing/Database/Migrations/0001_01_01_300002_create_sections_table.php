<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('page_id');
            $table->string('type');
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('content');
            $table->string('media_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('universe_visibility')->default('all');
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('marketing_pages')->cascadeOnDelete();
            $table->unique(['page_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
