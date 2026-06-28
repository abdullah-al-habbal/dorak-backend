<?php
// modules/Ban/Database/Migrations/0001_01_01_200091_create_bans_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bannable_id');
            $table->string('bannable_type');
            $table->text('reason')->nullable();
            $table->timestamp('banned_from')->useCurrent();
            $table->timestamp('banned_until')->nullable();
            $table->foreignUuid('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();

            $table->index(['bannable_id', 'bannable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bans');
    }
};
