<?php

// modules/OfferedService/Database/Migrations/0001_01_01_000001_create_offered_services_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offered_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('serviceable_id');
            $table->string('serviceable_type');
            $table->json('name');
            $table->json('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->foreignUuid('currency_id')->constrained('currencies');
            $table->unsignedInteger('duration')->nullable();
            $table->boolean('at_home')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offered_services');
    }
};
