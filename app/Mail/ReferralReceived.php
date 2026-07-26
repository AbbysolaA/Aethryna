<?php

namespace App\Mail;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Referral $referral)
    {
    }

    public function build()
    {
        return $this
            ->subject('New referral: ' . $this->referral->referred_first_name)
            ->replyTo($this->referral->referrer_email, $this->referral->referrer_name)
            ->markdown('emails.referral-received');
    }
}
