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

    public function toMail(object $notifiable): \App\Mail\WelcomeEmail
    {
        return new \App\Mail\WelcomeEmail($notifiable);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
