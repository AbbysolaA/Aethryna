<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\VolunteerApplicationController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/pathway', [PageController::class, 'pathway'])->name('pathway');
Route::get('/programs', [PageController::class, 'programs'])->name('programs');
Route::get('/impact', [PageController::class, 'impact'])->name('impact');
Route::get('/stories', [PageController::class, 'stories'])->name('stories');

// Sessions & Events
Route::get('/sessions', [PageController::class, 'sessions'])->name('sessions');
Route::post('/sessions/register', [PageController::class, 'registerSession'])->name('sessions.register');

// Waitlist
Route::post('/waitlist', [WaitlistController::class, 'store'])->name('waitlist.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // /admin on its own 404'd. It now lands on the dashboard, and because it
    // sits inside this group a signed-out visitor is sent to login first and
    // returned here afterwards, which makes /admin a usable way in.
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/content', [AdminController::class, 'content'])->name('content');

    // Staff and access. Admin, coach, mentor and safeguarding all reach other
    // people's records, so none of them can be self-served. Invited here; the
    // invitee sets their own password and no admin ever sees it.
    Route::get('/staff', [\App\Http\Controllers\Admin\StaffInviteController::class, 'index'])
        ->name('staff.index');
    Route::get('/staff/invite', [\App\Http\Controllers\Admin\StaffInviteController::class, 'create'])
        ->name('staff.create');
    Route::post('/staff/invite', [\App\Http\Controllers\Admin\StaffInviteController::class, 'store'])
        ->name('staff.store');
    Route::post('/staff/{user}/resend', [\App\Http\Controllers\Admin\StaffInviteController::class, 'resend'])
        ->name('staff.resend');
    Route::patch('/staff/{user}', [\App\Http\Controllers\Admin\StaffInviteController::class, 'update'])
        ->name('staff.update');

    // Volunteering roster: extend offers, track onboarding returns, close out
    // finished engagements. Mentor recruitment runs through here too.
    Route::get('/volunteers', [\App\Http\Controllers\Admin\VolunteerAdminController::class, 'index'])
        ->name('volunteers.index');
    Route::get('/volunteers/offer', [\App\Http\Controllers\Admin\VolunteerAdminController::class, 'create'])
        ->name('volunteers.create');
    Route::post('/volunteers/offer', [\App\Http\Controllers\Admin\VolunteerAdminController::class, 'store'])
        ->name('volunteers.store');
    Route::patch('/volunteers/{engagement}', [\App\Http\Controllers\Admin\VolunteerAdminController::class, 'update'])
        ->name('volunteers.update');
    // Mis-sent offers, withdrawn ones and test data. Cascades logged hours.
    Route::delete('/volunteers/{engagement}', [\App\Http\Controllers\Admin\VolunteerAdminController::class, 'destroy'])
        ->name('volunteers.destroy');
    // Turn an application into an offer. Separate from store() because the
    // engagement already exists; this only mints the token and sends the email.
    Route::get('/volunteers/{engagement}/extend', [\App\Http\Controllers\Admin\VolunteerAdminController::class, 'extendForm'])
        ->name('volunteers.extend.form');
    Route::post('/volunteers/{engagement}/extend', [\App\Http\Controllers\Admin\VolunteerAdminController::class, 'extend'])
        ->name('volunteers.extend');

    // Volunteer positions. Roles are database rows so a new position can be
    // posted without a deploy. Bound by slug.
    Route::get('/volunteer-roles', [\App\Http\Controllers\Admin\VolunteerRoleAdminController::class, 'index'])
        ->name('volunteer-roles.index');
    Route::get('/volunteer-roles/create', [\App\Http\Controllers\Admin\VolunteerRoleAdminController::class, 'create'])
        ->name('volunteer-roles.create');
    Route::post('/volunteer-roles', [\App\Http\Controllers\Admin\VolunteerRoleAdminController::class, 'store'])
        ->name('volunteer-roles.store');
    Route::get('/volunteer-roles/{role}/edit', [\App\Http\Controllers\Admin\VolunteerRoleAdminController::class, 'edit'])
        ->name('volunteer-roles.edit');
    Route::patch('/volunteer-roles/{role}', [\App\Http\Controllers\Admin\VolunteerRoleAdminController::class, 'update'])
        ->name('volunteer-roles.update');
    Route::delete('/volunteer-roles/{role}', [\App\Http\Controllers\Admin\VolunteerRoleAdminController::class, 'destroy'])
        ->name('volunteer-roles.destroy');

    // Onboarding pack. Uploads land on a private disk; the welcome email lists
    // whatever is active here, in sort order.
    Route::get('/volunteer-documents', [\App\Http\Controllers\Admin\VolunteerDocumentAdminController::class, 'index'])
        ->name('volunteer-documents.index');
    Route::post('/volunteer-documents', [\App\Http\Controllers\Admin\VolunteerDocumentAdminController::class, 'store'])
        ->name('volunteer-documents.store');
    Route::patch('/volunteer-documents/{document}', [\App\Http\Controllers\Admin\VolunteerDocumentAdminController::class, 'update'])
        ->name('volunteer-documents.update');
    Route::delete('/volunteer-documents/{document}', [\App\Http\Controllers\Admin\VolunteerDocumentAdminController::class, 'destroy'])
        ->name('volunteer-documents.destroy');

    // Organisational risk register. Separate from safeguarding concerns: a
    // concern is an incident about a named person, a risk is an organisational
    // exposure.
    Route::get('/risks', [\App\Http\Controllers\RiskController::class, 'index'])->name('risks.index');
    Route::get('/risks/create', [\App\Http\Controllers\RiskController::class, 'create'])->name('risks.create');
    Route::post('/risks', [\App\Http\Controllers\RiskController::class, 'store'])->name('risks.store');
    Route::get('/risks/{risk}/edit', [\App\Http\Controllers\RiskController::class, 'edit'])->name('risks.edit');
    Route::patch('/risks/{risk}', [\App\Http\Controllers\RiskController::class, 'update'])->name('risks.update');
    Route::delete('/risks/{risk}', [\App\Http\Controllers\RiskController::class, 'destroy'])->name('risks.destroy');
    
    // Pathway Management
    Route::get('/content/pathway/create', [AdminController::class, 'createPathway'])->name('content.pathway.create');
    Route::post('/content/pathway', [AdminController::class, 'storePathway'])->name('content.pathway.store');
    Route::get('/content/pathway/{pathway}/edit', [AdminController::class, 'editPathway'])->name('content.pathway.edit');
    Route::put('/content/pathway/{pathway}', [AdminController::class, 'updatePathway'])->name('content.pathway.update');
    Route::delete('/content/pathway/{pathway}', [AdminController::class, 'destroyPathway'])->name('content.pathway.destroy');

    // Question Management
    Route::get('/content/question/create', [AdminController::class, 'createQuestion'])->name('content.question.create');
    Route::post('/content/question', [AdminController::class, 'storeQuestion'])->name('content.question.store');
    Route::get('/content/question/{question}/edit', [AdminController::class, 'editQuestion'])->name('content.question.edit');
    Route::put('/content/question/{question}', [AdminController::class, 'updateQuestion'])->name('content.question.update');
    Route::delete('/content/question/{question}', [AdminController::class, 'destroyQuestion'])->name('content.question.destroy');
});

// ── Accepting a staff invitation ─────────────────────────────────────────────
// Public and token-based, because the invitee has no way to sign in yet. The
// token is Laravel's password broker on a seven-day window rather than the
// sixty-minute reset one.
Route::get('/staff/invite/{token}', [\App\Http\Controllers\Auth\AcceptInviteController::class, 'show'])
    ->middleware('throttle:20,60')
    ->name('staff.invite.show');
Route::post('/staff/invite', [\App\Http\Controllers\Auth\AcceptInviteController::class, 'store'])
    ->middleware('throttle:10,60')
    ->name('staff.invite.store');

// ── Safeguarding review ──────────────────────────────────────────────────────
// The lead works through open concerns and records decisions against each one.
//
// Kept under the /admin prefix and the admin.safeguarding.* names it already
// had, so existing links and redirects still resolve, but gated on
// 'safeguarding' rather than 'admin'. Admins still pass. The point is that the
// safeguarding lead no longer has to be made a full admin, inheriting the user
// list and the risk register, in order to read concerns about named learners.
Route::middleware(['auth', 'verified', 'safeguarding'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/safeguarding', [\App\Http\Controllers\SafeguardingController::class, 'index'])
        ->name('safeguarding.index');
    // Choose who a concern is about. Declared before the {concern} route so
    // "record" is not swallowed as a concern id.
    Route::get('/safeguarding/record', [\App\Http\Controllers\SafeguardingController::class, 'picker'])
        ->name('safeguarding.picker');
    Route::get('/safeguarding/{concern}', [\App\Http\Controllers\SafeguardingController::class, 'show'])
        ->name('safeguarding.show');
    Route::patch('/safeguarding/{concern}', [\App\Http\Controllers\SafeguardingController::class, 'update'])
        ->name('safeguarding.update');
});

// Coach Routes
Route::middleware(['auth', 'verified', 'coach'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CoachController::class, 'dashboard'])->name('dashboard');
    Route::get('/cohort', [\App\Http\Controllers\CoachController::class, 'cohort'])->name('cohort');
    Route::post('/flag/{learner}', [\App\Http\Controllers\CoachController::class, 'flagConcern'])->name('flag');
});

// ── Safeguarding ─────────────────────────────────────────────────────────────
// Raising a concern is open to mentors, coaches and admins alike (the role
// check lives in SafeguardingController), because anyone who notices something
// should be able to escalate it. The concern is recorded in the database and
// the safeguarding lead is notified for review and decision.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/safeguarding/concern/{learner}', [\App\Http\Controllers\SafeguardingController::class, 'create'])
        ->name('safeguarding.create');
    Route::post('/safeguarding/concern/{learner}', [\App\Http\Controllers\SafeguardingController::class, 'store'])
        ->name('safeguarding.store');
});

// Mentor Routes
Route::middleware(['auth', 'verified', 'mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MentorController::class, 'dashboard'])->name('dashboard');
    Route::get('/learners', [\App\Http\Controllers\MentorController::class, 'learners'])->name('learners');
    Route::post('/log-session', [\App\Http\Controllers\MentorController::class, 'logSession'])->name('log-session');
});

// ── Volunteering ─────────────────────────────────────────────────────────────
// Mentors come through this pipeline too. A mentor is a volunteer role that
// additionally grants the /mentor area above on acceptance, so there is one
// offer-and-onboarding flow rather than two that drift apart.
//
// The claim link is deliberately public and unauthenticated. Volunteers reach
// us through partner organisations and panels as well as the website, so the
// page has to offer registration alongside sign-in. Throttled because the URL
// carries the offer token.
// Public front door. Anyone can put themselves forward against an open role;
// applying grants nothing, it creates a record an admin reads before deciding
// whether to extend an offer. Throttled like the referral form.
Route::get('/volunteer/apply', [VolunteerApplicationController::class, 'create'])->name('volunteer.apply');
Route::post('/volunteer/apply', [VolunteerApplicationController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('volunteer.apply.store');
Route::get('/volunteer/apply/thanks', [VolunteerApplicationController::class, 'thanks'])->name('volunteer.apply.thanks');

Route::get('/volunteer/offer/{token}', [VolunteerController::class, 'claim'])
    ->middleware('throttle:20,60')
    ->name('volunteer.claim');

// Sets a password and creates the account on the address the offer was sent
// to, so a volunteer never passes through the cohort application. Refuses if
// that address already has an account, which would be takeover by forwarded
// link rather than onboarding.
Route::post('/volunteer/offer/{token}', [VolunteerController::class, 'claimStore'])
    ->middleware('throttle:10,60')
    ->name('volunteer.claim.store');

// Explicit confirmation when the signed-in address is not the one the offer
// went to, rather than binding to whoever happens to be logged in.
Route::post('/volunteer/offer/{token}/continue', [VolunteerController::class, 'claimAs'])
    ->middleware(['auth', 'throttle:10,60'])
    ->name('volunteer.claim.as');

Route::middleware(['auth', 'verified'])->prefix('volunteer')->name('volunteer.')->group(function () {
    Route::get('/', [VolunteerController::class, 'index'])->name('index');
    // Onboarding pack files. They sit on a disk the web server does not serve,
    // so this route is the only way to reach one, and it checks the caller.
    Route::get('/documents/{document}', [VolunteerController::class, 'downloadDocument'])
        ->name('documents.download');
    Route::get('/engagement/{engagement}', [VolunteerController::class, 'show'])->name('show');
    Route::post('/engagement/{engagement}/respond', [VolunteerController::class, 'respond'])->name('respond');
    Route::post('/engagement/{engagement}/hours', [VolunteerController::class, 'storeHours'])->name('hours.store');
});


// Assessment Routes
Route::prefix('assessment')->name('assessment.')->group(function () {
    Route::get('/', [AssessmentController::class, 'index'])->name('index');
    Route::match(['GET', 'POST'], '/start', [AssessmentController::class, 'start'])->name('start');
    Route::get('/question/{question}', [AssessmentController::class, 'question'])->name('question');
    Route::post('/question/{question}/answer', [AssessmentController::class, 'answer'])->name('answer');
    Route::get('/results', [AssessmentController::class, 'results'])->name('results');
    Route::post('/reset', [AssessmentController::class, 'reset'])->name('reset');
});

// ── Partners ──────────────────────────────────────────────────────────────────
Route::get('/partners', [PageController::class, 'partners'])->name('partners');
Route::post('/partners/enquiry', [PageController::class, 'partnerEnquiry'])->name('partners.enquiry');

// ── AI Labs ───────────────────────────────────────────────────────────────────
Route::get('/ai-labs', [PageController::class, 'aiLabs'])->name('ai-labs');

// ── Mentors ───────────────────────────────────────────────────────────────────
// Public page explaining what mentoring involves and how to volunteer. The
// authenticated /mentor/* routes below are the logged-in mentor's own area and
// are unrelated.
Route::get('/mentors', [PageController::class, 'mentors'])->name('mentors');

// ── Partner referrals ────────────────────────────────────────────────────────
// Public form for partners (community orgs, DWP, church groups etc.) to refer
// someone who could benefit. Consent-gated for the referred person's contact;
// rate-limited to prevent abuse.
Route::get('/refer', [ReferralController::class, 'create'])->name('referral.create');
Route::post('/refer', [ReferralController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('referral.store');
Route::get('/refer/thanks', [ReferralController::class, 'thanks'])->name('referral.thanks');

// ── IndexNow verification file ───────────────────────────────────────────────
// Serves the key file that Bing / Yandex / Seznam fetch to prove ownership.
// The key lives in .env (INDEXNOW_KEY), never in git. Rotation is a single
// env change plus php artisan config:clear. Route is constrained to hex so
// it cannot shadow real static files.
Route::get('/{key}.txt', function (string $key) {
    $expected = config('services.indexnow.key');
    abort_unless($expected && hash_equals($expected, $key), 404);
    return response($expected, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
})->where('key', '[a-f0-9]{16,128}');

// ── XML sitemap ─────────────────────────────────────────────────────────────
// Generated from the same URL list used by IndexNow so both stay in sync.
// Add or remove pages in config/services.php > indexnow > urls, and both
// this sitemap and the next IndexNow push pick up the change automatically.
Route::get('/sitemap.xml', function () {
    $paths = config('services.indexnow.urls', []);
    $host  = config('services.indexnow.host', 'skillscoop.org');
    // Deploy time as the sitemap-wide lastmod — good enough for a small site
    // and honest (changes every deploy, stable between deploys).
    $lastmod = date('c', @filemtime(base_path('routes/web.php')) ?: time());

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($paths as $path) {
        $url = 'https://' . $host . '/' . ltrim($path, '/');
        $url = $path === '/' ? 'https://' . $host . '/' : rtrim($url, '/');
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
        $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>' . "\n";

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
});

// ── robots.txt ──────────────────────────────────────────────────────────────
// Points crawlers at the sitemap and hides files that look like pages but
// are not: search-console verification files and the IndexNow key file both
// have HTML/txt extensions and no meta or h1, which trips SEO scanners.
Route::get('/robots.txt', function () {
    $host = config('services.indexnow.host', 'skillscoop.org');

    // Paths excluded from every group. Declared once so the AI crawler group
    // below cannot drift from the wildcard group.
    $private = [
        '# Verification files — required for Search Console but not content.',
        'Disallow: /googlec84eff80aae46a44.html',
        '',
        '# Auth and account pages — not content, do not index.',
        'Disallow: /login',
        'Disallow: /register',
        'Disallow: /password/',
        'Disallow: /email/',
        'Disallow: /dashboard',
        'Disallow: /admin/',
        'Disallow: /profile',
        '',
        '# Volunteer offer links carry a single-use token, and the',
        '# engagement pages are private. /volunteer/apply stays crawlable.',
        'Disallow: /volunteer/offer/',
        'Disallow: /volunteer/engagement/',
    ];

    $body  = "User-agent: *\n";
    $body .= "Allow: /\n";
    $body .= "\n";
    $body .= implode("\n", $private) . "\n";
    $body .= "\n";

    // AI assistants and answer engines, named individually. The wildcard
    // group above already allows them, but readiness scanners and several
    // operators check for the agent by name and read its absence as
    // ambiguous. See config/agents.php to add or remove one.
    $crawlers = config('agents.crawlers', []);
    if ($crawlers) {
        $body .= "# AI assistants, answer engines and training crawlers are welcome\n";
        $body .= "# on public pages. Removing a name here does not block it — add an\n";
        $body .= "# explicit Disallow group for that agent instead.\n";
        foreach ($crawlers as $crawler) {
            $body .= "User-agent: {$crawler}\n";
        }
        $body .= "Allow: /\n";
        $body .= "\n";
        $body .= implode("\n", $private) . "\n";
        $body .= "\n";
    }

    $body .= "Sitemap: https://{$host}/sitemap.xml\n";
    return response($body, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});

// ── llms.txt ────────────────────────────────────────────────────────────────
// The AI-assistant equivalent of sitemap.xml: a plain-text brief a model can
// read in one fetch instead of crawling and guessing. Page list comes from the
// same IndexNow config the sitemap uses; descriptions live in config/agents.php.
Route::get('/llms.txt', function () {
    $org   = config('organisation');
    $host  = config('services.indexnow.host', 'skillscoop.org');
    $base  = 'https://' . $host;
    $pages = config('agents.pages', []);

    $body  = "# {$org['name']}\n\n";
    $body .= "> " . config('agents.summary') . "\n\n";

    foreach (config('agents.facts', []) as $fact) {
        $body .= "- {$fact}\n";
    }
    $body .= "\n## Pages\n\n";

    foreach (config('services.indexnow.urls', []) as $path) {
        $page  = $pages[$path] ?? [];
        $title = $page['title'] ?? trim($path, '/') ?: 'Home';
        $url   = $path === '/' ? $base . '/' : $base . '/' . ltrim($path, '/');

        $body .= "- [{$title}]({$url})";
        if (! empty($page['description'])) {
            $body .= ": {$page['description']}";
        }
        $body .= "\n";
    }

    $body .= "\n## Contact\n\n";
    $body .= "- Email: {$org['email']}\n";
    $body .= "- Location: {$org['locality']}, United Kingdom\n";
    foreach ($org['same_as'] ?? [] as $profile) {
        $body .= "- {$profile}\n";
    }

    return response($body, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});

// ── .well-known/agents.json ─────────────────────────────────────────────────
// Machine-readable card describing the organisation and how an agent reaches
// a human. Served from a route rather than a file because several hosts
// refuse to serve dot-directories out of the public root.
Route::get('/.well-known/agents.json', function () {
    $org  = config('organisation');
    $host = config('services.indexnow.host', 'skillscoop.org');
    $base = 'https://' . $host;

    return response()->json([
        'schema_version' => '1.0',
        'name'           => $org['name'],
        'description'    => config('agents.summary'),
        'url'            => $base,
        'capabilities'   => config('agents.capabilities', []),
        'contact'        => $org['email'],
        'documentation'  => $base . '/llms.txt',
        'endpoints'      => [
            'sitemap'  => $base . '/sitemap.xml',
            'llms_txt' => $base . '/llms.txt',
        ],
        'organization'   => [
            'legal_name' => $org['legal_name'],
            'location'   => $org['locality'] . ', United Kingdom',
            'same_as'    => $org['same_as'] ?? [],
        ],
        'policies'       => [
            'privacy'        => $base . '/privacy',
            'terms'          => $base . '/terms',
            'acceptable_use' => $base . '/acceptable-use',
        ],
    ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

// ── Legal pages ──────────────────────────────────────────────────────────────
Route::get('/privacy',         [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms',           [PageController::class, 'terms'])->name('terms');
Route::get('/cookies',         [PageController::class, 'cookies'])->name('cookies');
Route::get('/acceptable-use',  [PageController::class, 'acceptableUse'])->name('acceptable-use');

    // LinkedIn OAuth routes
    Route::get('/auth/linkedin', [App\Http\Controllers\LinkedInController::class, 'redirectToProvider'])
        ->name('login.linkedin');
    Route::get('/auth/linkedin/callback', [App\Http\Controllers\LinkedInController::class, 'handleProviderCallback'])
        ->name('login.linkedin.callback');

    // Google OAuth routes
Route::get('/auth/google', [App\Http\Controllers\GoogleController::class, 'redirectToProvider'])
    ->name('login.google');
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleController::class, 'handleProviderCallback'])
    ->name('login.google.callback');

require __DIR__.'/auth.php';
