<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referrer_name',
        'referrer_email',
        'referrer_organisation',
        'referrer_role',
        'is_self_referral',
        'referred_first_name',
        'referred_contact',
        'cohort',
        'context',
        'consent_confirmed',
        'source',
        'status',
    ];

    protected $casts = [
        'is_self_referral' => 'boolean',
        'consent_confirmed' => 'boolean',
    ];
}
