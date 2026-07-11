<?php

// modules/Booking/Database/Migrations/0001_01_01_000002_create_booking_offered_service_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_offered_service', function (Blueprint $table) {
            $table->foreignUuid('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignUuid('offered_service_id')->constrained('offered_services')->cascadeOnDelete();
            $table->primary(['booking_id', 'offered_service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_offered_service');
    }
};
