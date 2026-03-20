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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')            
               ->constrained()
               ->cascadeOnDelete();
            $table->foreignId('user_id')
               ->nullable()
               ->constrained()
               ->nullOnDelete();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->nullOnDelete();
            $table->text('content');
            $table->string('author_name_snapshot', 255);
            $table->enum('status', ['visible', 'hidden', 'deleted'])->default('visible');

            $table->timestamps();

            $table->index(['post_id', 'status', 'created_at']);
            $table->index(['parent_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
