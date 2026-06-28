<?php
// modules/Currency/Database/Migrations/0001_01_01_000002_create_exchange_rates_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('from_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->foreignUuid('to_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->decimal('rate', 20, 6);
            $table->timestamp('effective_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
