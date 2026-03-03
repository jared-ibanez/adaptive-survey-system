<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SurveyAnswer;
use App\Models\SurveySubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    private function baseCompletedQuery(Request $request)
    {
        $q = SurveySubmission::query()
            ->where('survey_submissions.status', 'completed')
            ->join('students', 'students.id', '=', 'survey_submissions.student_id');

        if ($request->filled('school')) {
            $q->where('students.school', $request->input('school'));
        }
        if ($request->filled('strand')) {
            $q->where('students.strand', $request->input('strand'));
        }
        if ($request->filled('gender')) {
            $q->where('students.gender', $request->input('gender'));
        }

        return $q;
    }

    private function resolveBandFromConfig(int $score, array $bands): ?string
    {
        foreach ($bands as $key => $meta) {
            $range = $meta['range'] ?? null;
            if (!is_array($range) || count($range) !== 2) {
                continue;
            }

            [$min, $max] = $range;

            if ($score >= (int) $min && $score <= (int) $max) {
                return (string) $key;
            }
        }

        return null;
    }

    /**
     * ---------- Reliability Helpers ----------
     */

    private function variance(array $values): ?float
    {
        $n = count($values);
        if ($n < 2) return null;

        $mean = array_sum($values) / $n;
        $sumSq = 0.0;
        foreach ($values as $v) {
            $sumSq += ($v - $mean) ** 2;
        }
        return $sumSq / ($n - 1); // sample variance
    }

    private function stddev(array $values): ?float
    {
        $var = $this->variance($values);
        return $var === null ? null : sqrt($var);
    }

    private function pearson(array $x, array $y): ?float
    {
        $n = count($x);
        if ($n < 3 || $n !== count($y)) return null;

        $mx = array_sum($x) / $n;
        $my = array_sum($y) / $n;

        $num = 0.0;
        $sx = 0.0;
        $sy = 0.0;

        for ($i = 0; $i < $n; $i++) {
            $dx = $x[$i] - $mx;
            $dy = $y[$i] - $my;
            $num += $dx * $dy;
            $sx += $dx ** 2;
            $sy += $dy ** 2;
        }

        if ($sx <= 0 || $sy <= 0) return null;
        return $num / sqrt($sx * $sy);
    }

    /**
     * Cronbach's alpha from matrix rows: [[item1,item2,...], ...]
     * Returns null if not computable.
     */
    private function cronbachAlpha(array $matrix): ?float
    {
        $n = count($matrix);
        if ($n < 3) return null;

        $k = count($matrix[0] ?? []);
        if ($k < 2) return null;

        // item variances
        $itemVars = [];
        for ($j = 0; $j < $k; $j++) {
            $col = [];
            foreach ($matrix as $row) {
                $col[] = $row[$j];
            }
            $v = $this->variance($col);
            if ($v === null) return null;
            $itemVars[] = $v;
        }

        // total scores variance
        $totals = [];
        foreach ($matrix as $row) {
            $totals[] = array_sum($row);
        }
        $totalVar = $this->variance($totals);
        if ($totalVar === null || $totalVar == 0.0) return null;

        $sumItemVars = array_sum($itemVars);

        $alpha = ($k / ($k - 1)) * (1 - ($sumItemVars / $totalVar));
        return $alpha;
    }

    /**
     * Build reliability package for a given range of question numbers.
     * Excludes any submission that has missing/null score on any item in the range.
     */
    private function reliabilityForRange(array $submissionIds, int $startQ, int $endQ): array
    {
        $k = ($endQ - $startQ) + 1;
        if ($k < 2 || empty($submissionIds)) {
            return [
                'n' => 0,
                'k' => $k,
                'alpha' => null,
                'items' => [],
            ];
        }

        // Fetch all answers for these submissions + this question range (including null scores)
        $rows = SurveyAnswer::whereIn('submission_id', $submissionIds)
            ->whereBetween('question_no', [$startQ, $endQ])
            ->get(['submission_id', 'question_no', 'score']);

        // Build per-submission map
        $bySub = [];
        foreach ($rows as $r) {
            $bySub[$r->submission_id][$r->question_no] = $r->score; // score can be null
        }

        // Build complete matrix (exclude any submission with missing or null)
        $matrix = [];
        $usedSubIds = [];
        foreach ($bySub as $sid => $map) {
            $row = [];
            $ok = true;
            for ($q = $startQ; $q <= $endQ; $q++) {
                if (!array_key_exists($q, $map) || $map[$q] === null) {
                    $ok = false;
                    break;
                }
                $row[] = (int) $map[$q];
            }
            if ($ok) {
                $matrix[] = $row;
                $usedSubIds[] = $sid;
            }
        }

        $n = count($matrix);
        $alpha = $this->cronbachAlpha($matrix);

        // Item diagnostics (Level 2): mean, sd, item-total correlation, alpha-if-deleted
        $items = [];
        if ($n >= 3 && $k >= 2) {
            // Precompute totals and totals-minus-item for each item
            $totals = array_map(fn($row) => array_sum($row), $matrix);

            for ($j = 0; $j < $k; $j++) {
                $itemScores = array_map(fn($row) => $row[$j], $matrix);
                $means = array_sum($itemScores) / $n;
                $sd = $this->stddev($itemScores);

                $totalMinus = [];
                for ($i = 0; $i < $n; $i++) {
                    $totalMinus[] = $totals[$i] - $matrix[$i][$j];
                }

                $r_it = $this->pearson($itemScores, $totalMinus);

                // alpha-if-deleted
                $matrixDeleted = [];
                for ($i = 0; $i < $n; $i++) {
                    $tmp = $matrix[$i];
                    array_splice($tmp, $j, 1);
                    $matrixDeleted[] = $tmp;
                }
                $alphaDeleted = $this->cronbachAlpha($matrixDeleted);

                $items[] = [
                    'question_no' => $startQ + $j,
                    'mean' => round($means, 3),
                    'sd' => $sd === null ? null : round($sd, 3),
                    'rit' => $r_it === null ? null : round($r_it, 3),
                    'alpha_if_deleted' => $alphaDeleted === null ? null : round($alphaDeleted, 3),
                ];
            }
        }

        return [
            'n' => $n,
            'k' => $k,
            'alpha' => $alpha === null ? null : round($alpha, 3),
            'items' => $items,
        ];
    }

    public function index(Request $request)
    {
        // Filter dropdown options
        $schools = Student::whereNotNull('school')->where('school', '!=', '')
            ->distinct()->orderBy('school')->pluck('school');

        $strands = Student::whereNotNull('strand')->where('strand', '!=', '')
            ->distinct()->orderBy('strand')->pluck('strand');

        $gender = Student::whereNotNull('gender')->where('gender', '!=', '')
            ->distinct()->orderBy('gender')->pluck('gender');

        $base = $this->baseCompletedQuery($request);

        // KPI
        $totalSubmissions = (clone $base)->count();
        $avgTotalScore = round((float) (clone $base)->avg('survey_submissions.total_score'), 2);

        // ✅ Pull interpretation config (5 levels)
        $cfg = config('survey');
        $overallBands = $cfg['overall']['bands'] ?? [];
        abort_if(empty($overallBands), 500, 'Survey overall interpretation config missing.');

        // ✅ Overall distribution (computed from total_score using config min/max)
        $totals = (clone $base)->pluck('survey_submissions.total_score')
            ->map(fn($v) => (int)$v)
            ->toArray();

        $bandCounts = [];
        foreach ($totals as $t) {
            $bandKey = $this->resolveBandFromConfig($t, $overallBands);

            // if score doesn't match any configured range, bucket it to "unknown"
            $bandKey = $bandKey ?? 'unknown';

            $bandCounts[$bandKey] = ($bandCounts[$bandKey] ?? 0) + 1;
        }

        // Dimension averages
        $dimensionAverages = (clone $base)->selectRaw("
            AVG(COALESCE(survey_submissions.decision_making_score,0)) as decision_making,
            AVG(COALESCE(survey_submissions.teamwork_respect_score,0)) as teamwork_respect,
            AVG(COALESCE(survey_submissions.learning_skills_score,0)) as learning_skills,
            AVG(COALESCE(survey_submissions.responsibility_score,0)) as responsibility,
            AVG(COALESCE(survey_submissions.flexible_thinking_score,0)) as flexible_thinking,
            AVG(COALESCE(survey_submissions.critical_creative_score,0)) as critical_creative
        ")->first();

        $dimensionAveragesArr = [
            'Decision Making' => round((float) $dimensionAverages->decision_making, 2),
            'Teamwork & Respect' => round((float) $dimensionAverages->teamwork_respect, 2),
            'Learning Skills' => round((float) $dimensionAverages->learning_skills, 2),
            'Responsibility' => round((float) $dimensionAverages->responsibility, 2),
            'Flexible Thinking' => round((float) $dimensionAverages->flexible_thinking, 2),
            'Critical & Creative Thinking' => round((float) $dimensionAverages->critical_creative, 2),
        ];

        // Submissions by School
        $submissionsBySchool = (clone $base)
            ->select('students.school', DB::raw('COUNT(*) as total'))
            ->groupBy('students.school')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        // Recent submissions list
        $recentSubmissions = (clone $base)
            ->select('survey_submissions.*', 'students.student_id', 'students.school', 'students.strand', 'students.gender')
            ->orderByDesc('survey_submissions.submitted_at')
            ->limit(8)
            ->get();

        // ✅ Chart payload (5-band, config order)
        $overallLabels = [];
        $overallData = [];

        foreach ($overallBands as $bandKey => $meta) {
            $overallLabels[] = $meta['label'] ?? $bandKey;
            $overallData[] = (int) ($bandCounts[$bandKey] ?? 0);
        }

        // Optional: surface out-of-range scores
        if (isset($bandCounts['unknown'])) {
            $overallLabels[] = 'Unclassified';
            $overallData[] = (int) $bandCounts['unknown'];
        }

        $dimLabels = array_keys($dimensionAveragesArr);
        $dimData = array_values($dimensionAveragesArr);

        $schoolLabels = $submissionsBySchool->pluck('school')->map(fn($s) => $s ?: '—')->values();
        $schoolData = $submissionsBySchool->pluck('total')->values();

        /**
         * Reliability (based on CURRENT FILTERS)
         */
        $submissionIds = (clone $base)->select('survey_submissions.id')->pluck('survey_submissions.id')->toArray();

        $reliabilityMap = [
            'Decision Making' => [1, 5],
            'Teamwork & Respect' => [6, 10],
            'Learning Skills' => [11, 15],
            'Responsibility' => [16, 20],
            'Flexible Thinking' => [21, 25],
            'Critical & Creative Thinking' => [26, 30],
        ];

        $reliability = [];
        foreach ($reliabilityMap as $label => [$startQ, $endQ]) {
            $reliability[$label] = $this->reliabilityForRange($submissionIds, $startQ, $endQ);
        }

        $overallReliability = $this->reliabilityForRange($submissionIds, 1, 30);

        return view('admin.dashboard', [
            'schools' => $schools,
            'strands' => $strands,
            'gender' => $gender,

            'filters' => [
                'school' => $request->input('school', ''),
                'strand' => $request->input('strand', ''),
                'gender' => $request->input('gender', ''),
            ],

            'totalSubmissions' => $totalSubmissions,
            'avgTotalScore' => $avgTotalScore,
            'dimensionAverages' => $dimensionAveragesArr,
            'recentSubmissions' => $recentSubmissions,

            'chart' => [
                'overallLabels' => $overallLabels,
                'overallData' => $overallData,
                'dimLabels' => $dimLabels,
                'dimData' => $dimData,
                'schoolLabels' => $schoolLabels,
                'schoolData' => $schoolData,
            ],

            'reliability' => $reliability,
            'overallReliability' => $overallReliability,
        ]);
    }


    public function submissions(Request $request)
    {
        $base = $this->baseCompletedQuery($request);

        $rows = (clone $base)
            ->select(
                'survey_submissions.*',
                'students.student_id',
                'students.school',
                'students.strand',
                'students.gender'
            )
            ->orderByDesc('survey_submissions.submitted_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.submissions.index', [
            'rows' => $rows,
            'filters' => [
                'school' => $request->input('school', ''),
                'strand' => $request->input('strand', ''),
                'gender' => $request->input('gender', ''),
            ],
        ]);
    }

    public function showSubmission(\App\Models\SurveySubmission $submission)
    {
        // Pull student + answers
        $student = \App\Models\Student::findOrFail($submission->student_id);

        $answers = \App\Models\SurveyAnswer::where('submission_id', $submission->id)
            ->orderBy('question_no')
            ->get(['question_no', 'score', 'custom_response']);

        // Group by dimension ranges (raw answers table)
        $groups = [
            'Decision Making (1–5)'                 => $answers->whereBetween('question_no', [1, 5])->values(),
            'Teamwork & Respect (6–10)'             => $answers->whereBetween('question_no', [6, 10])->values(),
            'Learning Skills (11–15)'               => $answers->whereBetween('question_no', [11, 15])->values(),
            'Responsibility (16–20)'                => $answers->whereBetween('question_no', [16, 20])->values(),
            'Flexible Thinking (21–25)'             => $answers->whereBetween('question_no', [21, 25])->values(),
            'Critical & Creative Thinking (26–30)'  => $answers->whereBetween('question_no', [26, 30])->values(),
        ];

        // Interpretation config
        $cfg = config('survey');
        abort_if(empty($cfg) || empty($cfg['dimensions']) || empty($cfg['overall']), 500, 'Survey interpretation config missing.');

        // Compute dimension scores from answers
        $dimensionItemMap = [
            'decision_making'   => [1, 5],
            'teamwork_respect'  => [6, 10],
            'learning_skills'   => [11, 15],
            'responsibility'    => [16, 20],
            'flexible_thinking' => [21, 25],
            'critical_creative' => [26, 30],
        ];

        $dimensionScores = [];
        foreach ($dimensionItemMap as $key => [$from, $to]) {
            $dimensionScores[$key] = (int) $answers
                ->whereBetween('question_no', [$from, $to])
                ->sum('score'); // null scores are ignored by sum()
        }

        // Resolver: range-based (matches your config: ['range' => [min,max]])
        $resolveBandByRange = function (int $score, array $bands): ?string {
            foreach ($bands as $bandKey => $meta) {
                $range = $meta['range'] ?? null;
                if (!is_array($range) || count($range) !== 2) continue;

                [$min, $max] = $range;

                if ($score >= (int) $min && $score <= (int) $max) {
                    return (string) $bandKey;
                }
            }
            return null;
        };

        // Build dimension descriptors (5 levels)
        $dimensionDescriptors = [];
        foreach ($dimensionScores as $key => $score) {
            $dimMeta  = $cfg['dimensions'][$key] ?? [];
            $dimBands = $dimMeta['bands'] ?? [];

            // fallback if score doesn't match any range
            $band = $resolveBandByRange($score, $dimBands) ?? array_key_first($dimBands);

            $dimensionDescriptors[$key] = [
                'key'   => $key,
                'score' => $score,
                'band'  => $band,
                'meta'  => $dimMeta,
                'data'  => ($band && isset($dimBands[$band])) ? $dimBands[$band] : [],
            ];
        }

        // Strengths (top 2) + Areas for Growth (bottom 2)
        $sortedDesc = $dimensionDescriptors;
        uasort($sortedDesc, fn($a, $b) => $b['score'] <=> $a['score']);
        $strengths = array_slice(array_values($sortedDesc), 0, 2);

        $sortedAsc = $dimensionDescriptors;
        uasort($sortedAsc, fn($a, $b) => $a['score'] <=> $b['score']);
        $areasForGrowth = array_slice(array_values($sortedAsc), 0, 2);

        // Overall interpretation (5 levels)
        $totalScore = array_sum($dimensionScores);

        $overallBands = $cfg['overall']['bands'] ?? [];
        $overallBand = $resolveBandByRange($totalScore, $overallBands) ?? array_key_first($overallBands);

        $overall = [
            'score' => $totalScore,
            'band'  => $overallBand,
            'data'  => ($overallBand && isset($overallBands[$overallBand])) ? $overallBands[$overallBand] : [],
        ];

        return view('admin.submissions.show', compact(
            'submission',
            'student',
            'groups',
            'dimensionDescriptors',
            'strengths',
            'areasForGrowth',
            'overall'
        ));
    }

}
