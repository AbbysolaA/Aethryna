<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One recap email for one panel to one address. See panels:recap. */
class PanelRecapSend extends Model
{
    protected $fillable = ['panel_session_id', 'email'];
}
