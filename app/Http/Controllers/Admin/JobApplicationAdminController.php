<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Working through applications for paid roles.
 *
 * Deliberately a flat list rather than a pipeline board. At this size the job
 * is reading each application and deciding, not managing stages, and the four
 * statuses exist so the list shows where everyone stands rather than to be
 * dragged between columns.
 */
class JobApplicationAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.job-applications.index', [
            // Unread first, then newest. The role is eager loaded because
            // every row names it.
            'applications' => JobApplication::with('role')
                ->orderByRaw("case when status = 'new' then 0 else 1 end")
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function update(Request $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(JobApplication::STATUSES)],
        ]);

        $application->update($validated);

        return redirect()
            ->route('admin.job-applications.index')
            ->with('status', $application->name.' marked '.$validated['status'].'.');
    }

    /**
     * The applicant's CV. Private disk, admin group, download rather than
     * inline: an unsolicited document from a stranger gains nothing by being
     * rendered in the browser.
     */
    public function downloadCv(JobApplication $application): StreamedResponse
    {
        abort_unless($application->hasCv(), 404);

        return Storage::disk(JobApplication::CV_DISK)->download(
            $application->cv_path,
            $application->cv_original_name ?: 'cv'
        );
    }
}
