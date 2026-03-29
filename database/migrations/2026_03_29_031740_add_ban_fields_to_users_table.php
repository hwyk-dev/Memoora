<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('banned_at')->nullable()->after('last_login_at');
            $table->timestamp('suspended_until')->nullable()->after('banned_at');
            $table->text('ban_reason')->nullable()->after('suspended_until');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['banned_at', 'suspended_until', 'ban_reason']);
        });
    }
};
