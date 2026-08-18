<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled work
|--------------------------------------------------------------------------
|
| None of this runs unless the server calls the scheduler once a minute. On
| the production host that means one crontab line:
|
|   * * * * * cd /home/aethryna/domains/skillscoop.org/public_html \
|       && php artisan schedule:run >> /dev/null 2>&1
|
| Without it the commands below are simply never invoked — no error, no mail,
| nothing to notice.
|
*/

// Reminds people who left the pathway assessment part way through, and stamps
// long-dead rows as abandoned so the in-progress count means something.
//
// Daily rather than hourly: the command only ever sends one reminder per
// assessment, so running it more often buys nothing and risks catching someone
// mid-assessment across a time zone. Late morning UK is a reasonable hour to
// land in an inbox.
Schedule::command('assessments:remind')
    ->dailyAt('10:15')
    ->timezone('Europe/London')
    ->withoutOverlapping();
