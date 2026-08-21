<?php

namespace App\Providers;

use App\Listeners\ClaimAnonymousAssessments;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registered by hand rather than left to discovery: bootstrap/app.php
        // does not call withEvents(), so Laravel never scans app/Listeners and
        // a listener dropped in there would silently never fire.
        Event::listen(Login::class, ClaimAnonymousAssessments::class);
        Event::listen(Registered::class, ClaimAnonymousAssessments::class);

        $this->registerRateLimiters();
    }

    /**
     * Named limiters for the assessment's identity endpoints.
     *
     * Named rather than the bare throttle:n,m form, because that form keys only
     * on the IP and so shares one counter with every other route using the same
     * numbers — somebody mistyping their email could find themselves blocked by
     * a limit they hit on an unrelated page.
     *
     * The numbers are set to stop scripted abuse, not to punish a typo: three
     * goes at an email address is normal, thirty is not.
     */
    private function registerRateLimiters(): void
    {
        RateLimiter::for('assessment-contact', fn (Request $request) => [
            Limit::perMinute(10)->by($request->ip()),
            Limit::perHour(40)->by($request->ip()),
        ]);

        // The resume token is a secret in a URL. Nobody legitimately opens more
        // than a handful of these, so guessing should stay expensive.
        RateLimiter::for('assessment-resume', fn (Request $request) => [
            Limit::perMinute(20)->by($request->ip()),
            Limit::perHour(60)->by($request->ip()),
        ]);

        // Event registration. Generous enough that a family or a keyworker
        // signing several people up from one address is not mistaken for an
        // attack, tight enough that the form is not a free mail relay.
        // Application forms with file uploads. Failed validation counts as an
        // attempt too, so the per-minute allowance leaves room for someone
        // fixing their mistakes without reuploading from a cafe connection
        // being mistaken for an attack.
        RateLimiter::for('job-apply', fn (Request $request) => [
            Limit::perMinute(6)->by($request->ip()),
            Limit::perHour(20)->by($request->ip()),
        ]);

        RateLimiter::for('speak-apply', fn (Request $request) => [
            Limit::perMinute(6)->by($request->ip()),
            Limit::perHour(20)->by($request->ip()),
        ]);

        RateLimiter::for('discovery-register', fn (Request $request) => [
            Limit::perMinute(6)->by($request->ip()),
            Limit::perHour(30)->by($request->ip()),
        ]);
    }
}
