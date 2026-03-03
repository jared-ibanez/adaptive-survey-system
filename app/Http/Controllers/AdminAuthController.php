<?php

namespace App\Http\Controllers;

use App\Models\AdminCred;
use Illuminate\Http\Request;
use App\Models\SurveySubmission;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:190'],
            'password' => ['required', 'string'],
        ]);

        // Plain-text match (as requested)
        $admin = AdminCred::where('email', $validated['email'])
            ->where('password', $validated['password'])
            ->first();

        if (!$admin) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        // Minimal session-based auth
        session([
            'admin_seq_id' => $admin->seq_id,
            'admin_email'  => $admin->email,
            'admin_name'   => trim(($admin->first_name ?? '').' '.($admin->last_name ?? '')),
            'admin_role'   => (int) $admin->role,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_seq_id',
            'admin_email',
            'admin_name',
            'admin_role',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

   public function dashboard()
    {
        abort_unless(session('admin_seq_id'), 403, 'Unauthorized.');

        // Total submissions
        $totalSubmissions = SurveySubmission::where('status', 'completed')->count();

        // Overall level distribution
        $levelDistribution = SurveySubmission::select('overall_level', DB::raw('COUNT(*) as total'))
            ->where('status', 'completed')
            ->groupBy('overall_level')
            ->pluck('total', 'overall_level');

        // Average total score
        $avgTotalScore = round(
            SurveySubmission::where('status', 'completed')->avg('total_score'),
            2
        );

        // Average per dimension
        $dimensionAverages = [
            'Decision Making' => round(SurveySubmission::avg('decision_making_score'), 2),
            'Teamwork & Respect' => round(SurveySubmission::avg('teamwork_respect_score'), 2),
            'Learning Skills' => round(SurveySubmission::avg('learning_skills_score'), 2),
            'Responsibility' => round(SurveySubmission::avg('responsibility_score'), 2),
            'Flexible Thinking' => round(SurveySubmission::avg('flexible_thinking_score'), 2),
            'Critical & Creative Thinking' => round(SurveySubmission::avg('critical_creative_score'), 2),
        ];

        // Recent submissions
        $recentSubmissions = SurveySubmission::latest('submitted_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSubmissions',
            'levelDistribution',
            'avgTotalScore',
            'dimensionAverages',
            'recentSubmissions'
        ));
    }
    
}
