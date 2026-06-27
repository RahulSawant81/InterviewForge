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
        Schema::table('resume_analyses', function (Blueprint $table) {

            $table->text('summary')
                ->nullable()
                ->after('overall_score');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume_analyses', function (Blueprint $table) {
            //
        });
    }
};
