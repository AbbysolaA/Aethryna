<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Risk extends Model
{
    protected $fillable = [
        'title', 'description', 'category',
        'likelihood', 'impact', 'mitigation',
        'residual_likelihood', 'residual_impact',
        'owner', 'review_due', 'status',
        'created_by_user_id', 'last_reviewed_by_user_id', 'last_reviewed_at',
    ];

    protected $casts = [
        'review_due'       => 'date',
        'last_reviewed_at' => 'datetime',
    ];

    public const CATEGORIES = [
        'delivery'     => 'Delivery',
        'safeguarding' => 'Safeguarding',
        'financial'    => 'Financial',
        'data'         => 'Data and IT',
        'reputational' => 'Reputational',
        'legal'        => 'Legal and compliance',
        'people'       => 'People',
        'partnership'  => 'Partnership',
    ];

    public const STATUSES = [
        'open'       => 'Open',
        'mitigating' => 'Mitigating',
        'monitoring' => 'Monitoring',
        'closed'     => 'Closed',
    ];

    public const SCALE = [
        1 => 'Very low',
        2 => 'Low',
        3 => 'Medium',
        4 => 'High',
        5 => 'Very high',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function lastReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_reviewed_by_user_id');
    }

    /** Inherent score, before mitigation. */
    public function getScoreAttribute(): int
    {
        return $this->likelihood * $this->impact;
    }

    /** Residual score, after mitigation. Falls back to inherent when not set. */
    public function getResidualScoreAttribute(): int
    {
        if ($this->residual_likelihood && $this->residual_impact) {
            return $this->residual_likelihood * $this->residual_impact;
        }

        return $this->score;
    }

    /**
     * Standard 5x5 banding. Anything scoring 15 or above needs a named owner
     * and a board-level conversation, not just a line in a spreadsheet.
     */
    public static function bandFor(int $score): string
    {
        return match (true) {
            $score >= 15 => 'critical',
            $score >= 8  => 'high',
            $score >= 4  => 'medium',
            default      => 'low',
        };
    }

    public function getBandAttribute(): string
    {
        return self::bandFor($this->residual_score);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->review_due
            && $this->status !== 'closed'
            && $this->review_due->isPast();
    }
}
