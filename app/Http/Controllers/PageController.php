<?php

namespace App\Http\Controllers;

use App\Events\SessionRegistered;
use App\Models\Pathway;
use App\Models\SessionRegistration;
use App\Services\EmailOctopusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function __construct(protected EmailOctopusService $emailOctopus)
    {
    }

    public function home()
    {
        $pathways = Pathway::active()->get();

        return view('home', compact('pathways'));
    }

    public function about()
    {
        return view('about');
    }

    public function pathway()
    {
        $pathways = \App\Models\Pathway::active()->get();

        return view('pathway', compact('pathways'));
    }

    public function programs()
    {
        $pathways = Pathway::active()->paginate(4);

        return view('programs', compact('pathways'));
    }

    /**
     * One course, on its own page.
     *
     * Every pathway had a name, a slug, a description, skills and career paths
     * and no address of its own — all seventeen lived four-at-a-time inside a
     * paginated list. So none could carry its own meta description, be shared,
     * or be found by its own name, and the assessment could tell somebody their
     * track was Cybersecurity Foundations while every link on offer took them
     * to page one, which does not mention it.
     *
     * Pilot tracks and the rest use the same template. The difference is what
     * it says: one is a course running in Cohort 1, the other a direction the
     * assessment can point somebody in. Pretending otherwise on thirteen pages
     * would be a promise the programme cannot keep.
     */
    public function program(Pathway $pathway)
    {
        abort_unless($pathway->is_active, 404);

        // Shown as "other tracks you could look at". Pilot tracks first,
        // because those are the ones somebody can actually start in January.
        $related = Pathway::active()
            ->where('id', '!=', $pathway->id)
            ->where('category', $pathway->category)
            ->orderByDesc('is_pilot')
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $related = $related->concat(
                Pathway::active()
                    ->where('id', '!=', $pathway->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderByDesc('is_pilot')
                    ->take(3 - $related->count())
                    ->get()
            );
        }

        return view('programs.show', [
            'pathway'     => $pathway,
            'related'     => $related,
            'pilotTracks' => Pathway::active()->pilot()->get(),
        ]);
    }

    public function impact()
    {
        return view('impact');
    }

    public function stories()
    {
        return view('stories');
    }

    public function sessions()
    {
        $upcoming = \App\Models\PanelSession::upcoming()->with(['speakers', 'media'])->get();
        $past     = \App\Models\PanelSession::past()->with(['speakers', 'images', 'videos'])->get();

        return view('sessions', compact('upcoming', 'past'));
    }

    /**
     * A single panel, at its own shareable URL.
     *
     * The sessions index only ever shows whichever panel is next, so a link
     * to it stops describing the event the moment the next one is scheduled.
     * This page keeps working: before the panel it takes registrations, after
     * it, it is the archive page for that panel and its recording.
     */
    public function session(\App\Models\PanelSession $panelSession)
    {
        // An event with a page of its own is served from there, not from here.
        // Two URLs rendering the same event would compete in search results and
        // split whatever links the event earns, so this one defers permanently.
        if ($panelSession->landing_path) {
            return redirect($panelSession->landing_path, 301);
        }

        $panelSession->load(['speakers', 'images', 'videos']);

        return view('sessions.show', ['session' => $panelSession]);
    }

    public function registerSession(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'interest_type' => 'required|in:learner,mentor,partner,curious',
            'referral_source' => 'nullable|string|max:255',
            'wants_to_speak' => 'nullable|boolean',
            'speaker_topic' => 'nullable|string|max:1000',
            'panel_session_id' => 'nullable|exists:panel_sessions,id',
        ]);

        // Send the confirmation email if there is a real upcoming panel to
        // confirm against. Previously the eventbrite URL was hard-coded to
        // Panel 1's ticket page, which meant everyone who registered after
        // Panel 1 finished got a broken link.
        //
        // A per-panel page posts its own panel_session_id so the registration
        // lands against the panel the person was actually looking at, not
        // whatever happens to be next by the time they submit.
        $nextSession = isset($validated['panel_session_id'])
            ? \App\Models\PanelSession::with('speakers')->find($validated['panel_session_id'])
            : \App\Models\PanelSession::upcoming()->with('speakers')->first();

        // Record it. This table existed and had never been written to, so
        // every registration before now survives only as an EmailOctopus
        // contact. Keyed on panel + email so someone registering twice
        // updates their entry instead of creating a duplicate.
        $wantsToSpeak = (bool) ($validated['wants_to_speak'] ?? false);

        SessionRegistration::updateOrCreate(
            [
                'panel_session_id' => $nextSession?->id,
                'email'            => $validated['email'],
            ],
            // Campaign attribution captured when they landed, distinct from
            // referral_source above, which is their own answer. Empty for an
            // untagged visit so re-registering keeps the original attribution.
            \App\Http\Middleware\CaptureUtmAttribution::forRegistration($request) + [
                'name'            => $validated['name'],
                'interest_type'   => $validated['interest_type'],
                'referral_source' => $validated['referral_source'] ?? null,
                'wants_to_speak'  => $wantsToSpeak,
                'speaker_topic'   => $wantsToSpeak ? ($validated['speaker_topic'] ?? null) : null,
            ]
        );

        if ($nextSession) {
            try {
                Mail::to($validated['email'])->send(new \App\Mail\SessionRegisteredMail(
                    $nextSession,
                    $validated['name'],
                    $validated['interest_type'],
                ));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Session registration email failed', [
                    'email' => $validated['email'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Sync to EmailOctopus (fails soft: never blocks the registration)
        $nameParts = preg_split('/\s+/', trim($validated['name']), 2);
        $this->emailOctopus->subscribe(
            $validated['email'],
            [
                'FirstName' => $nameParts[0] ?? '',
                'LastName' => $nameParts[1] ?? '',
            ],
            array_filter(['sessions', $validated['interest_type'], $wantsToSpeak ? 'speaker-interest' : null])
        );

        // Fire event after successful registration
        event(new SessionRegistered());

        // Anchor the redirect at the registration block, otherwise the success
        // message renders far below the fold and the user never sees it. A
        // registration made from a panel's own page returns there rather than
        // bouncing the person to the index.
        $back = isset($validated['panel_session_id']) && $nextSession
            ? route('sessions.show', $nextSession)
            : route('sessions');

        return redirect()->to($back . '#register-section')
            ->with('success', "Thank you for registering! We'll send you details about our next panel session to your email address.");
    }

    // ── AI Labs ──────────────────────────────────────────────────────────────
    public function aiLabs()
    {
        return view('ai-labs');
    }

    // ── Mentors ──────────────────────────────────────────────────────────────
    public function mentors()
    {
        return view('mentors');
    }

    // ── Partners ─────────────────────────────────────────────────────────────
    public function partners()
    {
        return view('partners');
    }

    public function partnerEnquiry(Request $request)
    {
        $validated = $request->validate([
            'organisation' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'brief_type'   => 'required|string|max:255',
            'message'      => 'required|string|max:3000',
        ]);

        Mail::raw(
            "New partner enquiry\n\nOrganisation: {$validated['organisation']}\nContact: {$validated['contact_name']}\nEmail: {$validated['email']}\nBrief type: {$validated['brief_type']}\n\n{$validated['message']}",
            fn ($m) => $m->to('hello@skillscoop.org')->subject("Partner enquiry from {$validated['organisation']}")
        );

        return redirect()->route('partners')->with('enquiry_sent', true);
    }

    // ── Legal pages ──────────────────────────────────────────────────────────
    public function privacy()
    {
        return view('legal.privacy');
    }

    public function terms()
    {
        return view('legal.terms');
    }

    public function cookies()
    {
        return view('legal.cookies');
    }

    public function acceptableUse()
    {
        return view('legal.acceptable-use');
    }
}
