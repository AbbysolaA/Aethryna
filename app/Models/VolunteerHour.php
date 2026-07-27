<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A logged block of volunteering time.
 *
 * Write-once by design. The volunteer confirms the figures are correct at
 * submission and the UI offers no edit, because these totals are used in
 * funder reporting. VolunteerController never calls update() on these.
 */
class VolunteerHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_engagement_id',
        'worked_on',
        'hours',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'worked_on' => 'date',
            'hours'     => 'decimal:2',
        ];
    }

    public function engagement(): BelongsTo
    {
        return $this->belongsTo(VolunteerEngagement::class, 'volunteer_engagement_id');
    }
}
