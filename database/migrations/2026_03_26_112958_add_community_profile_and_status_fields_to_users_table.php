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
            $table->text('bio')->nullable()->after('password');
            $table->string('avatar')->nullable()->after('bio');

            $table->enum('role', ['user', 'moderator', 'admin'])
                ->default('user')
                ->after('avatar');

            $table->enum('status', ['active', 'suspended', 'banned', 'withdrawn'])
                ->default('active')
                ->after('role');

            $table->timestamp('suspended_until')->nullable()->after('status');
            $table->text('current_sanction_reason')->nullable()->after('suspended_until');
            $table->timestamp('withdrawn_at')->nullable()->after('current_sanction_reason');
            $table->timestamp('purge_scheduled_at')->nullable()->after('withdrawn_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'avatar',
                'role',
                'status',
                'suspended_until',
                'current_sanction_reason',
                'withdrawn_at',
                'purge_scheduled_at',
            ]);
        });
    }
};
