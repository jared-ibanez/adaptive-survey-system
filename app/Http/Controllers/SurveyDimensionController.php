<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SurveyAnswer;
use App\Models\SurveySubmission;
use Illuminate\Http\Request;

class SurveyDimensionController extends Controller
{
    public function decisionMaking()
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $student = Student::findOrFail($studentDbId);

        // Optional: prefill saved answers for q1-5
        $submissionId = session('survey_submission_id');
        $answers = [];

        if ($submissionId) {
            $rows = SurveyAnswer::where('submission_id', $submissionId)
                ->whereBetween('question_no', [1, 5])
                ->get(['question_no', 'score', 'custom_response']);

            foreach ($rows as $row) {
                $answers[$row->question_no] = [
                    'score' => $row->score === null ? 'custom' : (string)$row->score,
                    'custom_response' => $row->custom_response ?? '',
                ];
            }
        }

        return view('survey.dimensions.decision-making', compact('student', 'answers'));
    }

    public function saveDecisionMaking(Request $request)
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        // Validate "score" for 1..5
        $request->validate([
            'q1.score' => ['required'],
            'q2.score' => ['required'],
            'q3.score' => ['required'],
            'q4.score' => ['required'],
            'q5.score' => ['required'],
        ]);

        // Create or resume an in-progress submission
        $submissionId = session('survey_submission_id');

        if ($submissionId) {
            $submission = SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();
        }

        if (empty($submission)) {
            $submission = SurveySubmission::firstOrCreate(
                ['student_id' => $studentDbId, 'status' => 'in_progress'],
                ['student_id' => $studentDbId, 'status' => 'in_progress']
            );

            session(['survey_submission_id' => $submission->id]);
        }

        // Upsert answers for Q1-5
        foreach ([1,2,3,4,5] as $qNo) {
            $scoreVal = data_get($request->all(), "q{$qNo}.score");
            $customText = data_get($request->all(), "q{$qNo}.custom_response");

            $isCustom = ($scoreVal === 'custom');

            SurveyAnswer::updateOrCreate(
                ['submission_id' => $submission->id, 'question_no' => $qNo],
                [
                    'score' => $isCustom ? null : (int)$scoreVal,
                    'custom_response' => $isCustom ? (string)($customText ?? '') : null,
                ]
            );
        }

        // Compute Decision Making score = sum of Q1–Q5 numeric scores (custom = null)
        $decisionMakingScore = SurveyAnswer::where('submission_id', $submission->id)
            ->whereBetween('question_no', [1, 5])
            ->sum('score');

        // Save the dimension score to survey_submissions
        $submission->decision_making_score = $decisionMakingScore;
        $submission->save();

        // Where to go next (you’ll create dimension 2 route/page next)
        $nextUrl = route('survey.dimension2');

        // AJAX response
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => $nextUrl,
            ]);
        }

        // Non-AJAX fallback
        return redirect($nextUrl);
    }

    public function teamworkRespect()
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $student = Student::findOrFail($studentDbId);

        $submissionId = session('survey_submission_id');
        $answers = [];

        if ($submissionId) {
            $submission = SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();

            if ($submission) {
                $rows = SurveyAnswer::where('submission_id', $submission->id)
                    ->whereBetween('question_no', [6, 10])
                    ->get(['question_no', 'score', 'custom_response']);

                foreach ($rows as $row) {
                    $answers[$row->question_no] = [
                        'score' => $row->score === null ? 'custom' : (string)$row->score,
                        'custom_response' => $row->custom_response ?? '',
                    ];
                }
            }
        }

        return view('survey.dimensions.teamwork-respect', compact('student', 'answers'));
    }

    public function saveTeamworkRespect(Request $request)
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $request->validate([
            'q6.score' => ['required'],
            'q7.score' => ['required'],
            'q8.score' => ['required'],
            'q9.score' => ['required'],
            'q10.score' => ['required'],
        ]);

        // Create or resume submission
        $submissionId = session('survey_submission_id');
        $submission = null;

        if ($submissionId) {
            $submission = SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();
        }

        if (!$submission) {
            $submission = SurveySubmission::firstOrCreate(
                ['student_id' => $studentDbId, 'status' => 'in_progress'],
                ['student_id' => $studentDbId, 'status' => 'in_progress']
            );

            session(['survey_submission_id' => $submission->id]);
        }

        // Upsert answers Q6–Q10
        foreach ([6, 7, 8, 9, 10] as $qNo) {
            $scoreVal = data_get($request->all(), "q{$qNo}.score");
            $customText = data_get($request->all(), "q{$qNo}.custom_response");

            $isCustom = ($scoreVal === 'custom');

            SurveyAnswer::updateOrCreate(
                ['submission_id' => $submission->id, 'question_no' => $qNo],
                [
                    'score' => $isCustom ? null : (int) $scoreVal,
                    'custom_response' => $isCustom ? (string) ($customText ?? '') : null,
                ]
            );
        }

        // Update Teamwork & Respect score (sum of numeric scores; custom=null treated as 0)
        $teamworkScore = SurveyAnswer::where('submission_id', $submission->id)
            ->whereBetween('question_no', [6, 10])
            ->sum('score');

        $submission->teamwork_respect_score = $teamworkScore;
        $submission->save();

        $nextUrl = route('survey.dimension3');

        return response()->json([
            'ok' => true,
            'redirect' => $nextUrl,
        ]);
    }

    public function learningSkills()
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $student = \App\Models\Student::findOrFail($studentDbId);

        $submissionId = session('survey_submission_id');
        $answers = [];

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();

            if ($submission) {
                $rows = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
                    ->whereBetween('question_no', [11, 15])
                    ->get(['question_no', 'score', 'custom_response']);

                foreach ($rows as $row) {
                    $answers[$row->question_no] = [
                        'score' => $row->score === null ? 'custom' : (string)$row->score,
                        'custom_response' => $row->custom_response ?? '',
                    ];
                }
            }
        }

        return view('survey.dimensions.learning-skills', compact('student', 'answers'));
    }

    public function saveLearningSkills(\Illuminate\Http\Request $request)
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $request->validate([
            'q11.score' => ['required'],
            'q12.score' => ['required'],
            'q13.score' => ['required'],
            'q14.score' => ['required'],
            'q15.score' => ['required'],
        ]);

        // Create or resume submission
        $submissionId = session('survey_submission_id');
        $submission = null;

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();
        }

        if (!$submission) {
            $submission = \App\Models\SurveySubmission::firstOrCreate(
                ['student_id' => $studentDbId, 'status' => 'in_progress'],
                ['student_id' => $studentDbId, 'status' => 'in_progress']
            );

            session(['survey_submission_id' => $submission->id]);
        }

        // Upsert answers Q11–Q15
        foreach ([11, 12, 13, 14, 15] as $qNo) {
            $scoreVal = data_get($request->all(), "q{$qNo}.score");
            $customText = data_get($request->all(), "q{$qNo}.custom_response");

            $isCustom = ($scoreVal === 'custom');

            \App\Models\SurveyAnswer::updateOrCreate(
                ['submission_id' => $submission->id, 'question_no' => $qNo],
                [
                    'score' => $isCustom ? null : (int) $scoreVal,
                    'custom_response' => $isCustom ? (string) ($customText ?? '') : null,
                ]
            );
        }

        // Update Learning Skills score (sum of numeric scores; custom=null treated as 0)
        $learningScore = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
            ->whereBetween('question_no', [11, 15])
            ->sum('score');

        $submission->learning_skills_score = $learningScore;
        $submission->save();

        $nextUrl = route('survey.dimension4');

        return response()->json([
            'ok' => true,
            'redirect' => $nextUrl,
        ]);
    }
    public function responsibility()
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $student = \App\Models\Student::findOrFail($studentDbId);

        $submissionId = session('survey_submission_id');
        $answers = [];

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();

            if ($submission) {
                $rows = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
                    ->whereBetween('question_no', [16, 20])
                    ->get(['question_no', 'score', 'custom_response']);

                foreach ($rows as $row) {
                    $answers[$row->question_no] = [
                        'score' => $row->score === null ? 'custom' : (string) $row->score,
                        'custom_response' => $row->custom_response ?? '',
                    ];
                }
            }
        }

        return view('survey.dimensions.responsibility', compact('student', 'answers'));
    }

    public function saveResponsibility(\Illuminate\Http\Request $request)
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $request->validate([
            'q16.score' => ['required'],
            'q17.score' => ['required'],
            'q18.score' => ['required'],
            'q19.score' => ['required'],
            'q20.score' => ['required'],
        ]);

        // Create or resume submission
        $submissionId = session('survey_submission_id');
        $submission = null;

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();
        }

        if (!$submission) {
            $submission = \App\Models\SurveySubmission::firstOrCreate(
                ['student_id' => $studentDbId, 'status' => 'in_progress'],
                ['student_id' => $studentDbId, 'status' => 'in_progress']
            );

            session(['survey_submission_id' => $submission->id]);
        }

        // Upsert answers Q16–Q20
        foreach ([16, 17, 18, 19, 20] as $qNo) {
            $scoreVal = data_get($request->all(), "q{$qNo}.score");
            $customText = data_get($request->all(), "q{$qNo}.custom_response");

            $isCustom = ($scoreVal === 'custom');

            \App\Models\SurveyAnswer::updateOrCreate(
                ['submission_id' => $submission->id, 'question_no' => $qNo],
                [
                    'score' => $isCustom ? null : (int) $scoreVal,
                    'custom_response' => $isCustom ? (string) ($customText ?? '') : null,
                ]
            );
        }

        // Update Responsibility score
        $respScore = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
            ->whereBetween('question_no', [16, 20])
            ->sum('score');

        $submission->responsibility_score = $respScore;
        $submission->save();

        $nextUrl = route('survey.dimension5');

        return response()->json([
            'ok' => true,
            'redirect' => $nextUrl,
        ]);
    }
    public function flexibleThinking()
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $student = \App\Models\Student::findOrFail($studentDbId);

        $submissionId = session('survey_submission_id');
        $answers = [];

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();

            if ($submission) {
                $rows = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
                    ->whereBetween('question_no', [21, 25])
                    ->get(['question_no', 'score', 'custom_response']);

                foreach ($rows as $row) {
                    $answers[$row->question_no] = [
                        'score' => $row->score === null ? 'custom' : (string) $row->score,
                        'custom_response' => $row->custom_response ?? '',
                    ];
                }
            }
        }

        return view('survey.dimensions.flexible-thinking', compact('student', 'answers'));
    }

    public function saveFlexibleThinking(\Illuminate\Http\Request $request)
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $request->validate([
            'q21.score' => ['required'],
            'q22.score' => ['required'],
            'q23.score' => ['required'],
            'q24.score' => ['required'],
            'q25.score' => ['required'],
        ]);

        // Create or resume submission
        $submissionId = session('survey_submission_id');
        $submission = null;

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();
        }

        if (!$submission) {
            $submission = \App\Models\SurveySubmission::firstOrCreate(
                ['student_id' => $studentDbId, 'status' => 'in_progress'],
                ['student_id' => $studentDbId, 'status' => 'in_progress']
            );

            session(['survey_submission_id' => $submission->id]);
        }

        // Upsert answers Q21–Q25
        foreach ([21, 22, 23, 24, 25] as $qNo) {
            $scoreVal = data_get($request->all(), "q{$qNo}.score");
            $customText = data_get($request->all(), "q{$qNo}.custom_response");

            $isCustom = ($scoreVal === 'custom');

            \App\Models\SurveyAnswer::updateOrCreate(
                ['submission_id' => $submission->id, 'question_no' => $qNo],
                [
                    'score' => $isCustom ? null : (int) $scoreVal,
                    'custom_response' => $isCustom ? (string) ($customText ?? '') : null,
                ]
            );
        }

        // Update Flexible Thinking score
        $flexScore = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
            ->whereBetween('question_no', [21, 25])
            ->sum('score');

        $submission->flexible_thinking_score = $flexScore;
        $submission->save();

        // ✅ Next: Dimension 6 (create next)
        $nextUrl = route('survey.dimension6');

        return response()->json([
            'ok' => true,
            'redirect' => $nextUrl,
        ]);
    }

    public function criticalCreativeThinking()
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $student = \App\Models\Student::findOrFail($studentDbId);

        $submissionId = session('survey_submission_id');
        $answers = [];

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();

            if ($submission) {
                $rows = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
                    ->whereBetween('question_no', [26, 30])
                    ->get(['question_no', 'score', 'custom_response']);

                foreach ($rows as $row) {
                    $answers[$row->question_no] = [
                        'score' => $row->score === null ? 'custom' : (string) $row->score,
                        'custom_response' => $row->custom_response ?? '',
                    ];
                }
            }
        }

        return view('survey.dimensions.critical-creative-thinking', compact('student', 'answers'));
    }

    public function saveCriticalCreativeThinking(\Illuminate\Http\Request $request)
    {
        $studentDbId = session('survey_student_db_id');
        abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

        $request->validate([
            'q26.score' => ['required'],
            'q27.score' => ['required'],
            'q28.score' => ['required'],
            'q29.score' => ['required'],
            'q30.score' => ['required'],
        ]);

        $submissionId = session('survey_submission_id');
        $submission = null;

        if ($submissionId) {
            $submission = \App\Models\SurveySubmission::where('id', $submissionId)
                ->where('student_id', $studentDbId)
                ->first();
        }

        if (!$submission) {
            $submission = \App\Models\SurveySubmission::firstOrCreate(
                ['student_id' => $studentDbId, 'status' => 'in_progress'],
                ['student_id' => $studentDbId, 'status' => 'in_progress']
            );

            session(['survey_submission_id' => $submission->id]);
        }

        // Upsert answers Q26–Q30
        foreach ([26, 27, 28, 29, 30] as $qNo) {
            $scoreVal = data_get($request->all(), "q{$qNo}.score");
            $customText = data_get($request->all(), "q{$qNo}.custom_response");

            $isCustom = ($scoreVal === 'custom');

            \App\Models\SurveyAnswer::updateOrCreate(
                ['submission_id' => $submission->id, 'question_no' => $qNo],
                [
                    'score' => $isCustom ? null : (int) $scoreVal,
                    'custom_response' => $isCustom ? (string) ($customText ?? '') : null,
                ]
            );
        }

        // Dimension 6 score
        $critCreativeScore = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
            ->whereBetween('question_no', [26, 30])
            ->sum('score');

        $submission->critical_creative_score = (int) $critCreativeScore;

        // Compute total score (max 90 if 6 dimensions × 15)
        $totalScore =
            (int) ($submission->decision_making_score ?? 0) +
            (int) ($submission->teamwork_respect_score ?? 0) +
            (int) ($submission->learning_skills_score ?? 0) +
            (int) ($submission->responsibility_score ?? 0) +
            (int) ($submission->flexible_thinking_score ?? 0) +
            (int) ($submission->critical_creative_score ?? 0);

        $submission->total_score = $totalScore;

        /**
         * ✅ NEW: 5-Level Adaptive Competency (from config)
         */
        $cfg = config('survey');
        $overallBands = $cfg['overall']['bands'] ?? [];

        $resolvedBandKey = null;
        $resolvedLabel = null;

        foreach ($overallBands as $bandKey => $meta) {
            $min = $meta['min'] ?? null;
            $max = $meta['max'] ?? null;

            if ($min === null || $max === null) continue;

            if ($totalScore >= (int)$min && $totalScore <= (int)$max) {
                $resolvedBandKey = $bandKey;
                $resolvedLabel = $meta['label'] ?? $bandKey;
                break;
            }
        }

        // Fallback safety
        if (!$resolvedLabel) {
            $resolvedBandKey = array_key_first($overallBands);
            $resolvedLabel = $overallBands[$resolvedBandKey]['label'] ?? 'Unknown';
        }

        $submission->overall_level = $resolvedLabel;

        // Finalize submission
        $submission->status = 'completed';
        $submission->submitted_at = now();
        $submission->save();

        $nextUrl = route('survey.thankyou', ['submission' => $submission->id]);

        return response()->json([
            'ok' => true,
            'redirect' => $nextUrl,
        ]);
    }


public function thankYou($submissionId)
{
    $studentDbId = session('survey_student_db_id');
    abort_if(!$studentDbId, 403, 'Student session not found. Please start the survey again.');

    // Load submission + answers (adjust relation name if needed)
    $submission = \App\Models\SurveySubmission::with(['answers']) // <- make sure this relation exists
        ->where('id', $submissionId)
        ->where('student_id', $studentDbId)
        ->firstOrFail();

    $cfg = config('survey');
    abort_if(empty($cfg) || empty($cfg['dimensions']) || empty($cfg['overall']), 500, 'Survey interpretation config missing.');

    // ✅ Updated surveys: 6 dimensions, 5 items each (1–30)
    $dimensionItemMap = [
        'decision_making'   => range(1, 5),
        'teamwork_respect'  => range(6, 10),
        'learning_skills'   => range(11, 15),
        'responsibility'    => range(16, 20),
        'flexible_thinking' => range(21, 25),
        'critical_creative' => range(26, 30),
    ];

    // Expect answers to have: item_no (or question_no) and value/score (1–5)
    // Change field names below if your schema differs.
    $answers = $submission->answers ?? collect();

    $dimensionScores = [];
    foreach ($dimensionItemMap as $dimKey => $items) {
        $dimensionScores[$dimKey] = (int) $answers
            ->whereIn('item_no', $items)   // <-- rename to question_no if needed
            ->sum('value');                // <-- rename to score if needed
    }

    // ✅ New dimension banding for 5–25
    $bandKey = function (int $score): string {
        if ($score >= 20) return 'high';
        if ($score >= 13) return 'moderate';
        return 'low';
    };

    $dimensionDescriptors = [];
    foreach ($dimensionScores as $key => $score) {
        $band = $bandKey($score);

        $dimensionDescriptors[$key] = [
            'key'   => $key,
            'score' => $score,
            'band'  => $band,
            'meta'  => $cfg['dimensions'][$key] ?? [],
            'data'  => $cfg['dimensions'][$key]['bands'][$band] ?? [],
        ];
    }

    // Strengths (top 2) and Growth (bottom 2)
    $sortedDesc = $dimensionDescriptors;
    uasort($sortedDesc, fn($a, $b) => $b['score'] <=> $a['score']);
    $strengths = array_slice(array_values($sortedDesc), 0, 2);

    $sortedAsc = $dimensionDescriptors;
    uasort($sortedAsc, fn($a, $b) => $a['score'] <=> $b['score']);
    $areasForGrowth = array_slice(array_values($sortedAsc), 0, 2);

    // ✅ Total for updated surveys (30–150 if 30 items × 1–5)
    $total = array_sum($dimensionScores);

    // ✅ New overall banding for 30–150
    $overallBand = $total >= 120 ? 'high' : ($total >= 90 ? 'moderate' : 'low');

    $overall = [
        'score' => $total,
        'band'  => $overallBand,
        'data'  => $cfg['overall']['bands'][$overallBand] ?? [],
    ];

    return view('survey.thank-you', [
        'submission'            => $submission,
        'dimensionDescriptors'  => $dimensionDescriptors,
        'overall'               => $overall,
        'strengths'             => $strengths,
        'areasForGrowth'        => $areasForGrowth,
    ]);
}



}
