<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submission_id')->constrained('survey_submissions')->cascadeOnDelete();

            $table->unsignedTinyInteger('question_no');        // 1..30
            $table->unsignedTinyInteger('score')->nullable();  // 1..3 (nullable while in-progress)

            // If they pick "I would respond differently", store their text here:
            $table->text('custom_response')->nullable();

            $table->timestamps();

            // Enforce 1 row per question per submission (so you can update on Next)
            $table->unique(['submission_id', 'question_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};

