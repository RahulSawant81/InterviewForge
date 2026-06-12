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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->nullOnDelete();

            $table->foreignId('state_id')
                ->nullable()
                ->constrained('states')
                ->nullOnDelete();

            $table->foreignId('city_id')
                ->nullable()
                ->constrained('cities')
                ->nullOnDelete();

            $table->string('profile_image')->nullable();

            $table->string('headline')->nullable();

            $table->decimal('experience_years', 4, 1)->nullable();

            $table->string('current_designation')->nullable();

            $table->string('current_company')->nullable();

            $table->decimal('current_ctc', 10, 2)->nullable();

            $table->decimal('expected_ctc', 10, 2)->nullable();

            $table->string('preferred_job_role')->nullable();

            $table->string('preferred_location')->nullable();

            $table->enum('experience_level', [
                'fresher',
                'junior',
                'mid',
                'senior',
                'lead',
            ])->nullable();

            $table->string('linkedin_url')->nullable();

            $table->string('github_url')->nullable();

            $table->string('portfolio_url')->nullable();

            $table->text('bio')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('country_id');
            $table->index('state_id');
            $table->index('city_id');
            $table->index('experience_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
