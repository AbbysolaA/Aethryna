<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpeakerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Reading speaker pitches and turning the good ones into bookings.
 *
 * Accepting goes through SpeakerApplication::accept(), which mints the
 * PanelSpeaker the session pages render and links it back to the pitch.
 * Attaching that speaker to a session stays in the existing panel admin,
 * because which session a talk fits is a programming decision, not a triage
 * one.
 */
class SpeakerApplicationAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.speaker-applications.index', [
            'applications' => SpeakerApplication::with('panelSpeaker')
                ->orderByRaw("case when status = 'new' then 0 else 1 end")
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function update(Request $request, SpeakerApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(SpeakerApplication::STATUSES)],
        ]);

        if ($validated['status'] === 'accepted') {
            $application->accept();

            return redirect()
                ->route('admin.speaker-applications.index')
                ->with('status', $application->name.' accepted. They are now on the speakers list, ready to attach to a session.');
        }

        $application->update($validated);

        return redirect()
            ->route('admin.speaker-applications.index')
            ->with('status', $application->name.' marked '.$validated['status'].'.');
    }

    /**
     * The uploaded headshot. Stays on the private disk even after acceptance:
     * publishing goes through the speakers photo command, which resizes and
     * strips it, not by serving a raw upload.
     */
    public function downloadHeadshot(SpeakerApplication $application): StreamedResponse
    {
        abort_unless($application->hasHeadshot(), 404);

        return Storage::disk(SpeakerApplication::HEADSHOT_DISK)->download(
            $application->headshot_path,
            $application->headshot_original_name ?: 'headshot'
        );
    }
}
