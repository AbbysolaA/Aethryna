<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SpeakerFutureSession;
use App\Models\SpeakerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    /**
     * One pitch, end to end on its own page: everything the person wrote,
     * their links, the headshot, and the decision controls. The index's
     * folded summaries were fine for triage and useless for actually
     * reading, which is the point of a pitch.
     */
    public function show(SpeakerApplication $application): View
    {
        return view('admin.speaker-applications.show', [
            'application' => $application->load('panelSpeaker'),
            // For the accept-onto-a-panel select: the panels a new speaker
            // could still actually appear on.
            'upcomingPanels' => \App\Models\PanelSession::upcoming()->get(),
        ]);
    }

    public function update(Request $request, SpeakerApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'status'           => ['required', Rule::in(SpeakerApplication::STATUSES)],
            'panel_session_id' => ['nullable', 'exists:panel_sessions,id'],
        ]);

        $back = $request->headers->get('referer')
            ? redirect()->back()
            : redirect()->route('admin.speaker-applications.index');

        if ($validated['status'] === 'accepted') {
            $speaker = $application->accept();

            // Accept and assign in one motion. The pitch already names what
            // they would speak to, so the pivot topic comes from the talk
            // title rather than being retyped. syncWithoutDetaching, so
            // accepting twice cannot double them onto a panel.
            if (! empty($validated['panel_session_id'])) {
                $panel = \App\Models\PanelSession::findOrFail($validated['panel_session_id']);

                $panel->speakers()->syncWithoutDetaching([
                    $speaker->id => [
                        'topic'      => str($application->talk_title)->limit(250)->toString(),
                        'sort_order' => ((int) $panel->speakers()->max('panel_session_speakers.sort_order')) + 1,
                    ],
                ]);

                return $back->with('status', $application->name.' accepted and added to '.$panel->tagline.'. Their details are on the speakers list; adjust the running order in Panels.');
            }

            return $back->with('status', $application->name.' accepted. They are on the speakers list with everything from the pitch; attach them to a panel from Panels whenever you are ready.');
        }

        $wasFuture = $application->status === 'future';
        $application->update($validated);

        // The status only means something if the speaker hears it: kept for
        // later experienced as silence reads as a decline. Sent once per move
        // into the status, not again on every later save.
        if ($validated['status'] === 'future' && ! $wasFuture) {
            try {
                Mail::to($application->email)->send(new SpeakerFutureSession($application));
            } catch (\Throwable $e) {
                Log::error('Speaker future-session email failed', [
                    'application' => $application->id,
                    'error'       => $e->getMessage(),
                ]);
            }

            return $back->with('status', $application->name.' kept for a future session, and emailed to say so.');
        }

        return $back->with('status', $application->name.' marked '.($application->statusLabel()).'.');
    }

    /**
     * The whole pitch as a plain text file, for sharing with co-organisers
     * or reading away from the admin.
     */
    public function downloadPitch(SpeakerApplication $application): StreamedResponse
    {
        $a = $application;

        $lines = [
            'SPEAKER PITCH: '.$a->name,
            'Submitted '.$a->created_at->format('j F Y, g.ia').' | Status: '.$a->statusLabel(),
            str_repeat('=', 60),
            '',
            'CONTACT',
            'Email: '.$a->email,
            $a->phone ? 'Phone: '.$a->phone : null,
            $a->organisation ? 'Organisation: '.$a->organisation : null,
            $a->job_title ? 'Role: '.$a->job_title : null,
            $a->location ? 'Based in: '.$a->location : null,
            $a->linkedin_url ? 'LinkedIn: '.$a->linkedin_url : null,
            $a->website_url ? 'Website: '.$a->website_url : null,
            '',
            'BIO',
            $a->bio,
            '',
            'THE TALK: '.$a->talk_title,
            '',
            $a->talk_summary,
            '',
            'Format preference: '.($a->formatLabel() ?: 'No preference'),
            'Topic areas: '.(is_array($a->topic_areas) && $a->topic_areas ? implode(', ', $a->topic_areas) : 'Not stated'),
            $a->prior_speaking ? '' : null,
            $a->prior_speaking ? 'PRIOR SPEAKING' : null,
            $a->prior_speaking,
            $a->video_url ? 'Video: '.$a->video_url : null,
            '',
            'Consent to contact: '.($a->consented_at?->format('j F Y') ?: 'not recorded'),
            'Recording consent: '.($a->recording_consented_at?->format('j F Y') ?: 'not recorded'),
        ];

        $content = implode("\n", array_filter($lines, fn ($l) => $l !== null));

        return response()->streamDownload(
            fn () => print($content),
            'pitch-'.str($a->name)->slug().'.txt',
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }

    /**
     * The headshot inline, for the detail page's preview. The download
     * route below stays the way to save the original file.
     */
    public function headshotPreview(SpeakerApplication $application)
    {
        abort_unless($application->hasHeadshot(), 404);

        return Storage::disk(SpeakerApplication::HEADSHOT_DISK)->response(
            $application->headshot_path,
            $application->headshot_original_name,
            ['Content-Type' => $application->headshot_mime ?: 'image/jpeg']
        );
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
