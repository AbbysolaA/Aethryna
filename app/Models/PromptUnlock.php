<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Someone who gave their details to see a session's prompts. */
class PromptUnlock extends Model
{
    protected $fillable = ['panel_session_id', 'name', 'email', 'opted_in'];

    protected $casts = ['opted_in' => 'boolean'];
}
