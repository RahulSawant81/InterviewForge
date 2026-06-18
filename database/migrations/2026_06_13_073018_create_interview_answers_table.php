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
        Schema::create('interview_answers', function (Blueprint $table) {

            $table->id();
            $table->foreignId('interview_question_id')->constrained()->cascadeOnDelete();
            $table->longText('answer')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->text('feedback')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('interview_question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_answers');
    }
};
