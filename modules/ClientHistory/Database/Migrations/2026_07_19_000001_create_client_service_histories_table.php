<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_service_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignUuid('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignUuid('barber_id')->constrained('barbers')->cascadeOnDelete();
            $table->foreignUuid('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignUuid('offered_service_id')->nullable()->constrained('offered_services')->nullOnDelete();
            $table->foreignId('catalog_item_id')->nullable()->constrained('service_catalog_items')->nullOnDelete();
            $table->timestamp('performed_at');
            $table->unsignedTinyInteger('client_rating')->nullable();
            $table->text('client_notes')->nullable();
            $table->text('barber_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_service_histories');
    }
};
