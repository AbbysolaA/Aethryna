<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use Illuminate\Http\Request;

/**
 * Organisational risk register.
 *
 * Distinct from safeguarding concerns: a concern is an incident about a named
 * individual, a risk is an organisational exposure such as "insufficient
 * mentor pipeline for Cohort 1". They are separate registers on purpose.
 *
 * Admin-only, via the admin middleware on the route group.
 */
class RiskController extends Controller
{
    public function index(Request $request)
    {
        $status   = $request->query('status', 'active');
        $category = $request->query('category');

        $query = Risk::query();

        if ($status === 'active') {
            $query->where('status', '!=', 'closed');
        } elseif (array_key_exists($status, Risk::STATUSES)) {
            $query->where('status', $status);
        } elseif ($status === 'overdue') {
            $query->where('status', '!=', 'closed')
                  ->whereNotNull('review_due')
                  ->whereDate('review_due', '<', now());
        }

        if ($category && array_key_exists($category, Risk::CATEGORIES)) {
            $query->where('category', $category);
        }

        // Highest residual exposure first. COALESCE so risks without a
        // residual score fall back to their inherent score rather than zero.
        $risks = $query
            ->orderByRaw('(COALESCE(residual_likelihood, likelihood) * COALESCE(residual_impact, impact)) DESC')
            ->orderBy('review_due')
            ->paginate(25)
            ->withQueryString();

        $counts = [
            'active'     => Risk::where('status', '!=', 'closed')->count(),
            'open'       => Risk::where('status', 'open')->count(),
            'mitigating' => Risk::where('status', 'mitigating')->count(),
            'monitoring' => Risk::where('status', 'monitoring')->count(),
            'closed'     => Risk::where('status', 'closed')->count(),
            'overdue'    => Risk::where('status', '!=', 'closed')
                                ->whereNotNull('review_due')
                                ->whereDate('review_due', '<', now())
                                ->count(),
            'all'        => Risk::count(),
        ];

        return view('admin.risks.index', compact('risks', 'status', 'category', 'counts'));
    }

    public function create()
    {
        return view('admin.risks.form', ['risk' => new Risk()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRisk($request);

        $validated['created_by_user_id'] = auth()->id();

        $risk = Risk::create($validated);

        return redirect()
            ->route('admin.risks.index')
            ->with('success', 'Risk R-' . $risk->id . ' added to the register.');
    }

    public function edit(Risk $risk)
    {
        return view('admin.risks.form', compact('risk'));
    }

    public function update(Request $request, Risk $risk)
    {
        $validated = $this->validateRisk($request);

        $validated['last_reviewed_by_user_id'] = auth()->id();
        $validated['last_reviewed_at']         = now();

        $risk->update($validated);

        return redirect()
            ->route('admin.risks.index')
            ->with('success', 'Risk R-' . $risk->id . ' updated and marked reviewed.');
    }

    public function destroy(Risk $risk)
    {
        $id = $risk->id;
        $risk->delete();

        return redirect()
            ->route('admin.risks.index')
            ->with('success', 'Risk R-' . $id . ' removed from the register.');
    }

    protected function validateRisk(Request $request): array
    {
        return $request->validate([
            'title'               => ['required', 'string', 'max:200'],
            'description'         => ['nullable', 'string', 'max:5000'],
            'category'            => ['required', 'in:' . implode(',', array_keys(Risk::CATEGORIES))],
            'likelihood'          => ['required', 'integer', 'between:1,5'],
            'impact'              => ['required', 'integer', 'between:1,5'],
            'mitigation'          => ['nullable', 'string', 'max:5000'],
            'residual_likelihood' => ['nullable', 'integer', 'between:1,5'],
            'residual_impact'     => ['nullable', 'integer', 'between:1,5'],
            'owner'               => ['nullable', 'string', 'max:120'],
            'review_due'          => ['nullable', 'date'],
            'status'              => ['required', 'in:' . implode(',', array_keys(Risk::STATUSES))],
        ]);
    }
}
