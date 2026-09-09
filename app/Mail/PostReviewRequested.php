<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The nudge that makes review work.
 *
 * A submitted post that nobody knows about is a post that waits forever and
 * a writer who concludes the review step is where work goes to die. The
 * admin inbox hears the moment something is submitted, once per submission
 * rather than on every save while it waits.
 */
class PostReviewRequested extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected Post $post, protected User $submitter)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.post-review-requested', $data)
            ->text('emails.post-review-requested-text', $data);
    }

    protected function messagePayload(): array
    {
        return [
            'subject'      => 'Blog post waiting for review: '.$this->post->title,
            'preheader'    => 'Read it, then press publish or send it back.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you administer skillscoop.org.',
            'year'         => date('Y'),

            'writerName' => $this->submitter->name,
            'postTitle'  => $this->post->title,
            'standfirst' => $this->post->standfirst,
            'minutes'    => $this->post->readingMinutes(),
            'previewUrl' => $this->post->url(),
            'editUrl'    => route('admin.posts.edit', $this->post),
        ];
    }
}
