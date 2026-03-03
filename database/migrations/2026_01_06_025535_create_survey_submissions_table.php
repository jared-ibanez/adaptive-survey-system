<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            // status allows saving while clicking Next
            $table->string('status', 20)->default('in_progress'); 
            // in_progress | submitted

            // Scores
            $table->unsignedSmallInteger('total_score')->nullable();
            $table->string('overall_level', 20)->nullable(); // High | Moderate | Low

            $table->unsignedTinyInteger('decision_making_score')->nullable();       // 5-15
            $table->unsignedTinyInteger('teamwork_respect_score')->nullable();      // 5-15
            $table->unsignedTinyInteger('learning_skills_score')->nullable();       // 5-15
            $table->unsignedTinyInteger('responsibility_score')->nullable();        // 5-15
            $table->unsignedTinyInteger('flexible_thinking_score')->nullable();     // 5-15
            $table->unsignedTinyInteger('critical_creative_score')->nullable();     // 5-15

            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // One active in-progress per student (optional but helpful)
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_submissions');
    }
};

