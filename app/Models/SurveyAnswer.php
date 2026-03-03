<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = [
        'submission_id',
        'question_no',
        'score',
        'custom_response',
    ];
}
