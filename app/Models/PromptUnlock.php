<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Someone who gave their details to see a session's prompts. */
class PromptUnlock extends Model
{
    protected $fillable = [
        'panel_session_id', 'name', 'email', 'opted_in',
        'utm_source', 'utm_medium', 'utm_campaign', 'referrer_host',
    ];

    protected $casts = ['opted_in' => 'boolean'];

    /** Where they came from, in plain words: "LinkedIn", "Direct or unknown". */
    public function sourceLabel(): string
    {
        return \App\Support\TrafficSource::label($this->utm_source, $this->referrer_host);
    }
}
