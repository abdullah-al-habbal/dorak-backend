<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('section_id')->nullable();
            $table->string('author_name');
            $table->json('author_title')->nullable();
            $table->json('quote');
            $table->string('avatar_url')->nullable();
            $table->integer('rating')->default(5);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections')->nullOnDelete();
            $table->unique(['section_id', 'author_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
