<?php

namespace App\Console\Commands;

use App\Mail\NewPostPublished;
use App\Models\BlogSubscriber;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Sends newly published posts to the blog's subscribers.
 *
 * Runs from the scheduler rather than inside the publish request, because
 * pressing publish should take a moment however long the list gets, and a
 * mail provider having a bad afternoon should delay the emails, not lose
 * them: an unstamped post is retried on the next run.
 *
 * A post is sent once, ever. subscribers_notified_at is stamped after the
 * send loop, and scheduling a post into the past or unpublishing and
 * republishing does not reset it.
 */
class NotifyBlogSubscribersCommand extends Command
{
    protected $signature = 'blog:notify-subscribers {--dry-run : List what would be sent without sending}';

    protected $description = 'Email newly published blog posts to active subscribers';

    /**
     * A post older than this that was somehow never sent is announced to
     * nobody: "new post" emails about last month are worse than none, and
     * the guard means a restored backup cannot flood every inbox.
     */
    private const STALE_DAYS = 14;

    public function handle(): int
    {
        if (config('mail.default') === 'log') {
            $this->warn('MAIL_MAILER is "log": anything sent below lands in the log file, not an inbox.');
        }

        $due = Post::published()
            ->whereNull('subscribers_notified_at')
            ->orderBy('published_at')
            ->get();

        if ($due->isEmpty()) {
            $this->info('Nothing to send.');

            return self::SUCCESS;
        }

        $subscribers = BlogSubscriber::active()->get();

        foreach ($due as $post) {
            if ($post->published_at->lt(now()->subDays(self::STALE_DAYS))) {
                if (! $this->option('dry-run')) {
                    $post->forceFill(['subscribers_notified_at' => now()])->save();
                }
                $this->warn("Skipped \"{$post->title}\": published more than ".self::STALE_DAYS.' days ago, stamped without sending.');

                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("Would send \"{$post->title}\" to {$subscribers->count()} subscriber(s).");

                continue;
            }

            $sent = 0;

            foreach ($subscribers as $subscriber) {
                try {
                    Mail::to($subscriber->email)->send(new NewPostPublished($post, $subscriber));
                    $sent++;
                } catch (\Throwable $e) {
                    // One bad address must not stop the rest of the list.
                    Log::error('Blog subscriber email failed', [
                        'post'       => $post->id,
                        'subscriber' => $subscriber->id,
                        'error'      => $e->getMessage(),
                    ]);
                }
            }

            $post->forceFill(['subscribers_notified_at' => now()])->save();

            $this->info("Sent \"{$post->title}\" to {$sent} of {$subscribers->count()} subscriber(s).");
        }

        return self::SUCCESS;
    }
}
