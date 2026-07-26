<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReferralController;
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
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/content', [AdminController::class, 'content'])->name('content');
    
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

// Coach Routes
Route::middleware(['auth', 'verified', 'coach'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CoachController::class, 'dashboard'])->name('dashboard');
    Route::get('/cohort', [\App\Http\Controllers\CoachController::class, 'cohort'])->name('cohort');
    Route::post('/flag/{learner}', [\App\Http\Controllers\CoachController::class, 'flagConcern'])->name('flag');
});

// Mentor Routes
Route::middleware(['auth', 'verified', 'mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MentorController::class, 'dashboard'])->name('dashboard');
    Route::get('/learners', [\App\Http\Controllers\MentorController::class, 'learners'])->name('learners');
    Route::post('/log-session', [\App\Http\Controllers\MentorController::class, 'logSession'])->name('log-session');
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
    $body  = "User-agent: *\n";
    $body .= "Allow: /\n";
    $body .= "\n";
    $body .= "# Verification files — required for Search Console but not content.\n";
    $body .= "Disallow: /googlec84eff80aae46a44.html\n";
    $body .= "\n";
    $body .= "# Auth and account pages — not content, do not index.\n";
    $body .= "Disallow: /login\n";
    $body .= "Disallow: /register\n";
    $body .= "Disallow: /password/\n";
    $body .= "Disallow: /email/\n";
    $body .= "Disallow: /dashboard\n";
    $body .= "Disallow: /admin/\n";
    $body .= "Disallow: /profile\n";
    $body .= "\n";
    $body .= "Sitemap: https://{$host}/sitemap.xml\n";
    return response($body, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
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
