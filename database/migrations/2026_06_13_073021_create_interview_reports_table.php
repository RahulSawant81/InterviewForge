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
        Schema::create('interview_reports', function (Blueprint $table) {

            $table->id();

            $table->foreignId('interview_id')->constrained()->cascadeOnDelete();

            $table->decimal('overall_score', 5, 2)->nullable();

            $table->json('strengths')->nullable();

            $table->json('weaknesses')->nullable();

            $table->json('recommendations')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('interview_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_reports');
    }
};
