<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoachController extends Controller
{

    /**
     * Coach Dashboard
     * - Monitor engagement
     * - Update progress markers
     * - Flag safeguarding
     */
    public function dashboard()
    {
        $coach = auth()->user();
        
        // Real data from database
        $totalLearners = $coach->cohortLearners()->where('status', 'active')->count();
        $atRiskCount = $coach->cohortLearners()->where('at_risk', true)->count();
        $avgEngagement = $coach->cohortLearners()->where('status', 'active')->avg('engagement_score') ?? 0;
        
        return view('coach.dashboard', compact('totalLearners', 'atRiskCount', 'avgEngagement'));
    }

    /**
     * Manage cohort of learners
     */
    public function cohort()
    {
        $coach = auth()->user();
        
        // Get learners with their cohort data
        $learners = \App\Models\CoachCohort::where('coach_id', $coach->id)
            ->with('learner')
            ->where('status', 'active')
            ->paginate(20);
        
        return view('coach.cohort', compact('learners'));
    }

    /**
     * Flag safeguarding concern
     */
    public function flagConcern(Request $request, \App\Models\User $learner)
    {
        $coach = auth()->user();
        
        // Update the cohort record to flag as at-risk
        $cohortRecord = \App\Models\CoachCohort::where('coach_id', $coach->id)
            ->where('learner_id', $learner->id)
            ->first();
        
        if ($cohortRecord) {
            $cohortRecord->update([
                'at_risk' => true,
                'risk_notes' => $request->input('notes', 'Flagged by coach on ' . now()->format('Y-m-d'))
            ]);
        }

        // This marks the learner on the coach's own cohort list. It is NOT a
        // safeguarding concern: it writes no SafeguardingConcern record, sends
        // no notification, and never reaches the concerns register. It used to
        // claim "admin notified", which was untrue and meant a coach could
        // believe they had escalated something when nobody had been told.
        //
        // Engagement risk and safeguarding are deliberately separate. A learner
        // falling behind is not the same as a welfare concern, and conflating
        // them buries the second in the first. Use the safeguarding form to
        // escalate.
        return back()->with('success', 'Marked as at risk on your cohort list. This is not a safeguarding concern; use "Raise concern" to escalate one.');
    }
}
