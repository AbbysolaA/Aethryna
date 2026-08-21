<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'engagement_type',
        'summary',
        'description',
        'grants_access',
        'requires_dbs',
        'requires_nda',
        'is_open',
        'compensation',
        'employment_basis',
        'location',
        'reports_to',
        'apply_email',
        'apply_instructions',
        'closes_at',
        'sections',
    ];

    protected function casts(): array
    {
        return [
            'requires_dbs' => 'boolean',
            'requires_nda' => 'boolean',
            'is_open'      => 'boolean',
            'closes_at'    => 'date',
            'sections'     => 'array',
        ];
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(VolunteerEngagement::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Mentors get the learner-facing area, so acceptance has to be gated on a
     * cleared DBS regardless of what the role record says. Asked here rather
     * than read from requires_dbs directly so the rule cannot be turned off by
     * editing a row.
     */
    public function requiresDbs(): bool
    {
        return $this->requires_dbs || $this->grants_access === 'mentor';
    }

    // ── Paid roles ───────────────────────────────────────────────────────────

    /**
     * A post somebody is paid for, employed or contracted.
     *
     * Kept as a question rather than comparing the column at every call site,
     * because "not a volunteer" is the thing the rest of the code cares about
     * and a fourth engagement type should not mean hunting for string
     * comparisons.
     */
    public function isPaid(): bool
    {
        return $this->engagement_type !== 'volunteer';
    }

    /** Paid vacancies, newest first: careers listing. */
    public function scopePaid($query)
    {
        return $query->where('engagement_type', '!=', 'volunteer');
    }

    /**
     * Unpaid roles only.
     *
     * The volunteer application form asks for availability and takes no CV,
     * which is the wrong shape for a paid post — and a paid post appearing in
     * that dropdown would route a jobseeker into a volunteer pipeline. So the
     * two lists are explicitly separate rather than "everything that is open".
     */
    public function scopeVolunteer($query)
    {
        return $query->where('engagement_type', 'volunteer');
    }

    /**
     * Still accepting applications.
     *
     * is_open is the deliberate switch; closes_at is the one that operates
     * itself, so a closing date that passes over a weekend does not leave the
     * post advertised on Monday.
     */
    public function isAcceptingApplications(): bool
    {
        if (! $this->is_open) {
            return false;
        }

        return ! $this->closes_at || ! $this->closes_at->copy()->endOfDay()->isPast();
    }

    public function scopeAcceptingApplications($query)
    {
        return $query->where('is_open', true)
            ->where(fn ($q) => $q->whereNull('closes_at')->orWhereDate('closes_at', '>=', now()->toDateString()));
    }

    public function url(): string
    {
        return route('careers.show', $this);
    }
}
