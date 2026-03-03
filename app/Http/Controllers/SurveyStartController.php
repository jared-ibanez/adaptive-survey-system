<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class SurveyStartController extends Controller
{
    public function show()
    {
        return view('survey.start');
    }

  public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:50'],
            'strand'     => ['required', 'in:ABM,STEM,GAS,HUMSS,TVL,Arts & Design,Sports'],
            'gender'     => ['required', 'in:Male,Female'],
            'school'     => ['required', 'string', 'max:255'],
        ]);

        // Normalize
        $validated['student_id'] = trim($validated['student_id']);
        $validated['school'] = trim($validated['school']);

        /**
         * Create student if not exists; otherwise update demographic info.
         */
        $student = Student::updateOrCreate(
            ['student_id' => $validated['student_id']],
            [
                'strand' => $validated['strand'],
                'gender' => $validated['gender'],
                'school' => $validated['school'],
            ]
        );

        /**
         * IMPORTANT: reset submission session so a new student (or a new attempt)
         * doesn't inherit the previous student's submission_id and answers.
         */
        session()->forget('survey_submission_id');

        /**
         * Save current student into session
         */
        session([
            'survey_student_db_id' => $student->id,          // internal DB PK
            'survey_student_id'    => $student->student_id,  // the ID/LRN string
        ]);

        // Go to Dimension 1
        return redirect()->route('survey.dimension1');
    }

}
