<?php
// modules/Activation/Database/Migrations/0001_01_01_200090_create_activation_logs_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activation_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('activable_id');
            $table->string('activable_type');
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->foreignUuid('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('activated_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['activable_id', 'activable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activation_logs');
    }
};
