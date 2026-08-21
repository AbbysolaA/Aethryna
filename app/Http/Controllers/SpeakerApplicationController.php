<?php

namespace App\Http\Controllers;

use App\Mail\SpeakerApplicationConfirmation;
use App\Mail\SpeakerApplicationReceived;
use App\Models\SpeakerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * The public door for people who want to speak at our sessions.
 *
 * Speaker interest used to be a checkbox on the event registration form, which
 * captures a maybe and nothing a programme decision can be made from. This
 * takes a real pitch and gives it somewhere to land other than an inbox.
 */
class SpeakerApplicationController extends Controller
{
    public function create(): View
    {
        return view('speakers.apply');
    }

    public function store(Request $request): RedirectResponse
    {
        // Same honeypot convention as every other public form on the site.
        if ($request->filled('sp_reference')) {
            Log::info('Speaker application honeypot triggered', [
                'ip'         => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 120),
            ]);

            return redirect()->route('speakers.apply.thanks');
        }

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:40'],
            'organisation'   => ['nullable', 'string', 'max:255'],
            'job_title'      => ['nullable', 'string', 'max:255'],
            'location'       => ['nullable', 'string', 'max:255'],

            'bio'            => ['required', 'string', 'max:2000'],
            'linkedin_url'   => ['nullable', 'url', 'max:255'],
            'website_url'    => ['nullable', 'url', 'max:255'],

            'talk_title'     => ['required', 'string', 'max:255'],
            'talk_summary'   => ['required', 'string', 'max:4000'],

            'session_format' => ['nullable', Rule::in(array_keys(SpeakerApplication::FORMATS))],
            'topic_areas'    => ['nullable', 'array'],
            'topic_areas.*'  => [Rule::in(SpeakerApplication::TOPICS)],

            // Never required. A first-time speaker with lived experience of
            // our audience can be a better booking than a conference regular.
            'prior_speaking' => ['nullable', 'string', 'max:2000'],
            'video_url'      => ['nullable', 'url', 'max:255'],

            'headshot'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'consent'        => ['accepted'],

            // Separate from the data consent, because it is a different
            // promise. Asked at pitch time so nobody is booked, recorded and
            // then asked. See the migration for the fuller reasoning.
            'recording_consent' => ['accepted'],
        ], [
            'bio.required'          => 'Please tell us a little about yourself. A short paragraph is plenty.',
            'talk_title.required'   => 'Please give your talk a working title. It can change later.',
            'talk_summary.required' => 'Please tell us what you would cover and who it is for.',
            'headshot.mimes'        => 'Please upload the photo as a JPG, PNG or WebP.',
            'headshot.max'          => 'That photo is larger than 5MB. Please upload a smaller one.',
            'consent.accepted'      => 'Please confirm we can hold your details to consider your pitch.',
            'recording_consent.accepted' => 'Sessions are recorded and shared, so we need your OK on that before we can book you.',
        ]);

        // A second pitch from the same address while the first is unread gets
        // the same thanks and no second row. Declined or accepted speakers can
        // pitch again; a different talk is a different conversation.
        if (SpeakerApplication::where('email', $validated['email'])->unread()->exists()) {
            return redirect()->route('speakers.apply.thanks');
        }

        $headshot = $request->file('headshot');

        $application = SpeakerApplication::create([
            ...collect($validated)->except(['headshot', 'consent', 'recording_consent'])->all(),
            'recording_consented_at' => now(),
            'headshot_path'          => $headshot?->store('speaker-headshots', SpeakerApplication::HEADSHOT_DISK),
            'headshot_original_name' => $headshot?->getClientOriginalName(),
            'headshot_mime'          => $headshot?->getClientMimeType(),
            'headshot_size'          => $headshot?->getSize(),
            'status'                 => 'new',
            'consented_at'           => now(),
        ]);

        $this->sendEmails($application);

        return redirect()->route('speakers.apply.thanks');
    }

    public function thanks(): View
    {
        return view('speakers.apply-thanks');
    }

    /** The row is saved by this point; mail failure must not undo that. */
    private function sendEmails(SpeakerApplication $application): void
    {
        try {
            Mail::to($application->email)->send(new SpeakerApplicationConfirmation($application));
        } catch (\Throwable $e) {
            Log::error('Speaker application confirmation failed', [
                'application' => $application->id,
                'email'       => $application->email,
                'error'       => $e->getMessage(),
            ]);
        }

        try {
            Mail::to(config('organisation.email'))->send(new SpeakerApplicationReceived($application));
        } catch (\Throwable $e) {
            Log::error('Speaker application staff notification failed', [
                'application' => $application->id,
                'error'       => $e->getMessage(),
            ]);
        }
    }
}
