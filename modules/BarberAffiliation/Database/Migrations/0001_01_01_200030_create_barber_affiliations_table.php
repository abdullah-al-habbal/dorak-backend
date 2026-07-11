<?php

// modules/BarberAffiliation/Database/Migrations/0001_01_01_000001_create_barber_affiliations_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barber_affiliations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('barber_id')->constrained('barbers')->cascadeOnDelete();
            $table->uuid('affiliable_id');
            $table->string('affiliable_type');
            $table->string('status');
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barber_affiliations');
    }
};
