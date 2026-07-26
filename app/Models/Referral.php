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
        'referred_first_name',
        'referred_contact',
        'cohort',
        'context',
        'consent_confirmed',
        'source',
        'status',
    ];

    protected $casts = [
        'consent_confirmed' => 'boolean',
    ];
}
