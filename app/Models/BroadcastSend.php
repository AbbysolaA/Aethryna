<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One one-off email, by name, to one address. See panels:prompts-email. */
class BroadcastSend extends Model
{
    protected $fillable = ['broadcast', 'email'];
}
