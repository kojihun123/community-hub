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
        Schema::create('moderation_actions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('moderator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('report_id')
                ->nullable()
                ->constrained('reports')
                ->nullOnDelete();

            $table->morphs('actionable');

            $table->enum('action', ['hide', 'delete', 'restore', 'warn', 'suspend', 'ban']);

            $table->text('reason')->nullable();

            $table->timestamps();

            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_actions');
    }
};
