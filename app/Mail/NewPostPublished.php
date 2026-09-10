<?php

namespace App\Mail;

use App\Models\BlogSubscriber;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * A new post, delivered to a blog subscriber.
 *
 * The standfirst and a button rather than the whole article: the post lives
 * on the site, where the pictures, video embeds and share buttons work, and
 * a visit counts in the analytics where a fully-read email would be
 * invisible.
 *
 * List-Unsubscribe headers as well as the footer link, so Gmail and Apple
 * Mail can offer their own unsubscribe button. One click that works beats a
 * spam report every time.
 */
class NewPostPublished extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected Post $post, protected BlogSubscriber $subscriber)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();
        $unsubscribe = $this->subscriber->unsubscribeUrl();

        return $this
            ->subject($data['subject'])
            ->view('emails.new-post-published', $data)
            ->text('emails.new-post-published-text', $data)
            ->withSymfonyMessage(function ($message) use ($unsubscribe) {
                $message->getHeaders()->addTextHeader('List-Unsubscribe', '<'.$unsubscribe.'>');
                $message->getHeaders()->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');
            });
    }

    protected function messagePayload(): array
    {
        return [
            'subject'      => $this->post->title,
            'preheader'    => $this->post->standfirst,
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you subscribed to the Skills Co-op blog.',
            'year'         => date('Y'),

            'postTitle'      => $this->post->title,
            'standfirst'     => $this->post->standfirst,
            'authorName'     => $this->post->authorName(),
            'minutes'        => $this->post->readingMinutes(),
            'postUrl'        => $this->post->url(),
            'unsubscribeUrl' => $this->subscriber->unsubscribeUrl(),
        ];
    }
}
