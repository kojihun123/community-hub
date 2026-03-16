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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('author_name_snapshot');
            
            $table->enum('status', ['published', 'hidden', 'deleted'])->default('published');
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_pinned')->default(false);

            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('comment_count')->default(0);

            $table->timestamps();

            $table->index(['board_id', 'status', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['is_notice', 'is_pinned', 'created_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
