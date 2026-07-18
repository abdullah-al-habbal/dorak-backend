<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offered_services', function (Blueprint $table) {
            $table->foreignId('catalog_item_id')
                ->nullable()
                ->constrained('service_catalog_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('offered_services', function (Blueprint $table) {
            $table->dropForeign(['catalog_item_id']);
            $table->dropColumn('catalog_item_id');
        });
    }
};
