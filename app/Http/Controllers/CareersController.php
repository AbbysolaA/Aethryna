<?php

namespace App\Http\Controllers;

use App\Models\VolunteerRole;
use Illuminate\View\View;

/**
 * Paid vacancies.
 *
 * Separate from /volunteer/apply on purpose. That page lists unpaid roles and
 * its form asks for availability and takes no CV, which is the wrong set of
 * questions for someone applying for a salaried job — and putting a vacancy in
 * that dropdown would quietly route a jobseeker into the volunteer pipeline.
 *
 * /careers is also the URL people guess, type and link to, which matters more
 * here than anywhere else on the site: a job page that search engines and job
 * boards can find is the difference between an applicant pool and an empty one.
 */
class CareersController extends Controller
{
    public function index(): View
    {
        return view('careers.index', [
            'roles' => VolunteerRole::paid()
                ->acceptingApplications()
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function show(VolunteerRole $role): View
    {
        // A volunteer role has no salary, no closing date and no inbox to apply
        // to, so rendering one here would produce a job page with every
        // employment fact missing.
        abort_unless($role->isPaid(), 404);

        // A closed vacancy still resolves rather than 404s: the link is in
        // inboxes and on job boards by then, and "this has closed" is a better
        // answer to somebody who followed one than a dead end.
        return view('careers.show', ['role' => $role]);
    }
}
