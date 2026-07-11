<?php

// modules/Booking/Database/Migrations/0001_01_01_000001_create_bookings_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignUuid('chair_id')->nullable()->constrained('chairs')->nullOnDelete();
            $table->foreignUuid('barber_id')->nullable()->constrained('barbers')->nullOnDelete();
            $table->dateTime('time_slot');
            $table->string('status');
            $table->json('at_home_location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
