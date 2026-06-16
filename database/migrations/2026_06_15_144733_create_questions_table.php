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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('question_categories')->cascadeOnDelete();

            $table->string('title');
            $table->longText('question');
            $table->enum('difficulty', [
                'beginner',
                'intermediate',
                'advanced',
            ]);

            $table->enum('question_type', [
                'text',
                'mcq',
                'coding',
            ])->default('text');

            $table->json('options')->nullable();
            $table->text('correct_answer')->nullable();
            $table->text('expected_answer')->nullable();
            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index('difficulty');
            $table->index('question_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
