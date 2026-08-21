<?php

namespace App\Http\Controllers;

use App\Mail\JobApplicationConfirmation;
use App\Mail\JobApplicationReceived;
use App\Models\JobApplication;
use App\Models\VolunteerRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Applying for a paid role, on the vacancy page itself.
 *
 * The page used to say "email your CV to hr@" and stop. Every application
 * arrived as an inbox thread with no record of who applied for what or when,
 * triaged by the person whose lack of time is the reason the vacancy exists.
 * Now the application lands as a row an admin works through, and the applicant
 * gets a confirmation instead of silence.
 */
class JobApplicationController extends Controller
{
    public function store(Request $request, VolunteerRole $role): RedirectResponse
    {
        // Only a live vacancy takes applications. The page stops offering the
        // form when a role closes; this stops a held-open tab or a script from
        // applying anyway.
        abort_unless($role->isPaid(), 404);

        if (! $role->isAcceptingApplications()) {
            return redirect($role->url())
                ->with('error', 'This role has closed and is no longer accepting applications.');
        }

        // Honeypot, same convention as the volunteer form: invisible to real
        // users, answered as success so a script learns nothing, logged
        // because this branch is indistinguishable from success to whoever
        // submitted.
        if ($request->filled('jb_reference')) {
            Log::info('Job application honeypot triggered', [
                'ip'         => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 120),
            ]);

            return redirect($role->url())
                ->with('success', $this->thanksMessage());
        }

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:40'],
            'cover_note'    => ['required', 'string', 'max:4000'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],

            // Required here, unlike the volunteer form. Applying for a salaried
            // job with a CV is the norm and the job description asks for one;
            // it is the volunteer side where demanding paperwork turns away
            // the right people.
            'cv'            => ['required', 'file', 'mimes:pdf,doc,docx,odt,rtf', 'max:5120'],
            'consent'       => ['accepted'],
        ], [
            'cover_note.required' => 'Please tell us why this role fits you. A few sentences is fine.',
            'cv.required'         => 'Please attach your CV as a PDF or Word document.',
            'cv.mimes'            => 'Please upload your CV as a PDF or Word document.',
            'cv.max'              => 'That file is larger than 5MB. Please upload a smaller one.',
            'portfolio_url.url'   => 'Please give the portfolio as a full link, starting with https.',
            'consent.accepted'    => 'Please confirm we can hold your details to consider your application.',
        ]);

        // Someone reapplying while still in the running is answered with the
        // same thanks and no second row. It avoids duplicate entries in the
        // pipeline and does not reveal who is already on file. A declined
        // applicant can apply again; people improve.
        $alreadyInFlight = JobApplication::where('volunteer_role_id', $role->id)
            ->where('email', $validated['email'])
            ->inFlight()
            ->exists();

        if ($alreadyInFlight) {
            return redirect($role->url())->with('success', $this->thanksMessage());
        }

        $cv = $request->file('cv');

        $application = JobApplication::create([
            'volunteer_role_id' => $role->id,
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'phone'             => $validated['phone'] ?? null,
            'cover_note'        => $validated['cover_note'],
            'portfolio_url'     => $validated['portfolio_url'] ?? null,
            'cv_path'           => $cv->store('job-cvs', JobApplication::CV_DISK),
            'cv_original_name'  => $cv->getClientOriginalName(),
            'cv_mime'           => $cv->getClientMimeType(),
            'cv_size'           => $cv->getSize(),
            'status'            => 'new',
            'consented_at'      => now(),
        ]);

        $this->sendEmails($application, $role);

        return redirect($role->url())->with('success', $this->thanksMessage());
    }

    /**
     * Neither email may cost anyone their application. The row is saved by
     * this point; a mail failure is logged at error, because the applicant is
     * expecting a confirmation that is now not coming.
     */
    private function sendEmails(JobApplication $application, VolunteerRole $role): void
    {
        try {
            Mail::to($application->email)->send(new JobApplicationConfirmation($application, $role));
        } catch (\Throwable $e) {
            Log::error('Job application confirmation failed', [
                'application' => $application->id,
                'email'       => $application->email,
                'error'       => $e->getMessage(),
            ]);
        }

        try {
            Mail::to($role->apply_email ?: config('organisation.email'))
                ->send(new JobApplicationReceived($application, $role));
        } catch (\Throwable $e) {
            Log::error('Job application staff notification failed', [
                'application' => $application->id,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    private function thanksMessage(): string
    {
        return 'Your application is in. We have emailed you a confirmation, and we read every application we receive.';
    }
}
