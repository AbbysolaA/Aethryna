<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * Welcome notification sent when a new account is created.
 *
 * Deliberately NOT ShouldQueue. QUEUE_CONNECTION is 'database', and the
 * production host does not run a persistent queue worker, so a queued
 * notification would sit in the jobs table and never send. Sending inline
 * costs the user a moment on registration but actually arrives.
 *
 * The mail body itself is the branded Mailable in App\Mail\WelcomeEmail,
 * which extends the shared emails.layout shell and ships a plain-text
 * alternative.
 */
class WelcomeEmail extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * The recipient has to be set here, on the Mailable itself.
     *
     * When toMail() returns a Mailable rather than a MailMessage, Laravel's
     * mail channel calls $mailable->send() directly and never applies the
     * notifiable's address (Illuminate\Notifications\Channels\MailChannel).
     * Without the ->to() below the message goes out with no recipient at all,
     * and Symfony throws "An email must have a To, Cc, or Bcc header" —
     * uncaught, part way through registration, so the new account is created
     * and then the request 500s in the user's face.
     */
    public function toMail(object $notifiable): \App\Mail\WelcomeEmail
    {
        return (new \App\Mail\WelcomeEmail($notifiable))->to($notifiable->email);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
