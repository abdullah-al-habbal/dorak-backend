<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('status', 20)->default('enabled');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'status', 'deleted_at']);
        });
    }
};
