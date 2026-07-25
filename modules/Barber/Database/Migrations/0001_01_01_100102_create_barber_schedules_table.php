<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barber_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('barber_id')->index();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['barber_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barber_schedules');
    }
};
