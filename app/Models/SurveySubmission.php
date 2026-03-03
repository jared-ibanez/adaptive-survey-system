<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveySubmission extends Model
{
    protected $fillable = [
        'student_id',
        'status',
        'total_score',
        'overall_level',
        'decision_making_score',
        'teamwork_respect_score',
        'learning_skills_score',
        'responsibility_score',
        'flexible_thinking_score',
        'critical_creative_score',
        'submitted_at',
    ];

    public function answers()
    {
        return $this->hasMany(\App\Models\SurveyAnswer::class, 'submission_id');
    }
}
