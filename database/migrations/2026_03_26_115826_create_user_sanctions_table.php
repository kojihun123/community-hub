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
        Schema::create('user_sanctions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('moderator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('report_id')
                ->nullable()
                ->constrained('reports')
                ->nullOnDelete();

            $table->foreignId('moderation_action_id')
                ->nullable()
                ->constrained('moderation_actions')
                ->nullOnDelete();

            $table->enum('type', ['warning', 'suspension', 'ban']);

            $table->text('reason');

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->enum('status', ['active', 'expired', 'revoked'])
                ->default('active');

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sanctions');
    }
};
