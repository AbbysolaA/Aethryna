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

    public function registerSession(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'interest_type' => 'required|in:learner,mentor,partner,curious',
            'referral_source' => 'nullable|string|max:255',
        ]);

        // Send the confirmation email if there is a real upcoming panel to
        // confirm against. Previously the eventbrite URL was hard-coded to
        // Panel 1's ticket page, which meant everyone who registered after
        // Panel 1 finished got a broken link.
        $nextSession = \App\Models\PanelSession::upcoming()
            ->with('speakers')
            ->first();

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
            ['sessions', $validated['interest_type']]
        );

        // Fire event after successful registration
        event(new SessionRegistered());

        // Anchor the redirect at the registration block, otherwise the success
        // message renders far below the fold and the user never sees it.
        return redirect()->to(route('sessions') . '#register-section')
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
