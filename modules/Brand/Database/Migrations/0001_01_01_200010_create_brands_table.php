<?php
// modules/Brand/Database/Migrations/0001_01_01_000001_create_brands_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->constrained('clients')->cascadeOnDelete();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('logo')->nullable();
            $table->foreignUuid('base_currency_id')->constrained('currencies');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
