<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * One person's stint in one volunteer role.
 *
 * The offer can exist before the person does: user_id stays null until they
 * sign in or register through the claim link, because volunteers reach us
 * through partner orgs and panels as well as the website.
 */
class VolunteerEngagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_role_id',
        'user_id',
        'offer_name',
        'offer_email',
        'phone',
        'about',
        'availability',
        'experience',
        'cv_path',
        'cv_original_name',
        'cv_mime',
        'cv_size',
        'status',
        'offer_token',
        'offer_expires_at',
        'applied_at',
        'offer_extended_at',
        'offer_responded_at',
        'starts_on',
        'ends_on',
        'completed_at',
        'agreement_signed_at',
        'nda_signed_at',
        'dbs_cleared_at',
        'notes',
    ];

    protected $hidden = [
        'offer_token',
    ];

    protected function casts(): array
    {
        return [
            'offer_expires_at'    => 'datetime',
            'applied_at'          => 'datetime',
            'offer_extended_at'   => 'datetime',
            'offer_responded_at'  => 'datetime',
            'completed_at'        => 'datetime',
            'agreement_signed_at' => 'datetime',
            'nda_signed_at'       => 'datetime',
            'dbs_cleared_at'      => 'datetime',
            'starts_on'           => 'date',
            'ends_on'             => 'date',
        ];
    }

    // --- Relationships ---

    public function role(): BelongsTo
    {
        return $this->belongsTo(VolunteerRole::class, 'volunteer_role_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hours(): HasMany
    {
        return $this->hasMany(VolunteerHour::class);
    }

    // --- Scopes ---

    public function scopeClaimable(Builder $query): Builder
    {
        return $query->where('status', 'offer_extended')
            ->where(fn (Builder $q) => $q
                ->whereNull('offer_expires_at')
                ->orWhere('offer_expires_at', '>', now()));
    }

    // --- Offer lifecycle ---

    /**
     * Mint a single-use claim token and mark the offer as extended.
     */
    public function extendOffer(?int $expiresInDays = null): static
    {
        $days = $expiresInDays ?? (int) config('volunteering.offer_response_days', 14);

        $this->forceFill([
            'status'            => 'offer_extended',
            'offer_token'       => Str::random(64),
            'offer_expires_at'  => now()->addDays($days),
            'offer_extended_at' => now(),
        ])->save();

        return $this;
    }

    public function offerHasExpired(): bool
    {
        return $this->offer_expires_at !== null && $this->offer_expires_at->isPast();
    }

    public function offerIsOpen(): bool
    {
        return $this->status === 'offer_extended' && ! $this->offerHasExpired();
    }

    /**
     * Bind a still-unclaimed offer to the account that just authenticated.
     *
     * The email is not required to match. Someone may well apply from a
     * personal address and hold their site account under another, and locking
     * that down would strand them with no way through. The token is the
     * credential; binding is recorded so an admin can see who claimed it.
     */
    public function claimFor(User $user): static
    {
        if ($this->user_id === null) {
            $this->forceFill(['user_id' => $user->id])->save();
        }

        return $this;
    }

    public function accept(): static
    {
        $this->forceFill([
            'status'             => 'offer_accepted',
            'offer_responded_at' => now(),
            'offer_token'        => null,
            'offer_expires_at'   => null,
        ])->save();

        return $this;
    }

    public function decline(): static
    {
        $this->forceFill([
            'status'             => 'offer_declined',
            'offer_responded_at' => now(),
            'offer_token'        => null,
            'offer_expires_at'   => null,
        ])->save();

        return $this;
    }

    // --- Derived state ---

    public function wasAccepted(): bool
    {
        return in_array($this->status, ['offer_accepted', 'complete'], true);
    }

    /**
     * Whether the stint is live right now. Derived from the dates rather than
     * stored, so there is no scheduled job to flip a status and no way for the
     * timeline to disagree with starts_on.
     */
    public function isVolunteeringNow(): bool
    {
        if ($this->status !== 'offer_accepted') {
            return false;
        }

        $today = Carbon::today();

        if ($this->starts_on && $this->starts_on->gt($today)) {
            return false;
        }

        return ! ($this->ends_on && $this->ends_on->lt($today));
    }

    public function totalHours(): float
    {
        return (float) $this->hours()->sum('hours');
    }

    /**
     * Onboarding items still outstanding, in the order we chase them.
     *
     * @return list<string>
     */
    public function outstandingOnboarding(): array
    {
        if (! $this->wasAccepted()) {
            return [];
        }

        $outstanding = [];

        if ($this->agreement_signed_at === null) {
            $outstanding[] = 'Volunteer Agreement';
        }

        if ($this->role?->requires_nda && $this->nda_signed_at === null) {
            $outstanding[] = 'Non-Disclosure Agreement';
        }

        if ($this->role?->requiresDbs() && $this->dbs_cleared_at === null) {
            $outstanding[] = 'DBS check';
        }

        return $outstanding;
    }

    /**
     * The opportunity task list, ordered, with each step resolved to done,
     * current or upcoming. Drives the timeline on the engagement page.
     *
     * @return list<array{key:string,label:string,detail:string,state:string}>
     */
    public function timeline(): array
    {
        $fmt = fn (?Carbon $d): string => $d?->timezone('Europe/London')->format('j F Y') ?? '';

        $steps = [];

        if ($this->applied_at) {
            $steps[] = [
                'key'    => 'applied',
                'label'  => 'Application submitted',
                'detail' => 'Your application was submitted on ' . $fmt($this->applied_at) . '.',
                'state'  => 'done',
            ];
        }

        $steps[] = [
            'key'    => 'offered',
            'label'  => 'Offer extended',
            'detail' => $this->offer_extended_at
                ? 'We extended you an offer on ' . $fmt($this->offer_extended_at) . '.'
                : 'An offer has been prepared for you.',
            'state'  => 'done',
        ];

        if ($this->status === 'offer_declined') {
            $steps[] = [
                'key'    => 'declined',
                'label'  => 'Offer declined',
                'detail' => 'You declined this offer on ' . $fmt($this->offer_responded_at) . '.',
                'state'  => 'current',
            ];

            return $steps;
        }

        if ($this->status === 'withdrawn') {
            $steps[] = [
                'key'    => 'withdrawn',
                'label'  => 'Offer withdrawn',
                'detail' => 'This offer is no longer open. Get in touch if that is unexpected.',
                'state'  => 'current',
            ];

            return $steps;
        }

        $accepted = $this->wasAccepted();

        $steps[] = [
            'key'    => 'accepted',
            'label'  => 'Offer accepted',
            'detail' => $accepted
                ? 'You accepted the offer on ' . $fmt($this->offer_responded_at) . '.'
                : 'Accept or decline below.',
            'state'  => $accepted ? 'done' : 'current',
        ];

        $volunteering = $this->isVolunteeringNow();
        $complete     = $this->status === 'complete';

        $steps[] = [
            'key'    => 'volunteering',
            'label'  => 'Volunteering',
            'detail' => $this->volunteeringDetail($accepted, $fmt),
            'state'  => $complete ? 'done' : ($volunteering ? 'current' : 'upcoming'),
        ];

        $steps[] = [
            'key'    => 'complete',
            'label'  => 'Opportunity complete',
            'detail' => $complete
                ? 'This opportunity finished on ' . $fmt($this->completed_at) . '.'
                : ($this->ends_on
                    ? 'This opportunity will end on ' . $fmt($this->ends_on) . '.'
                    : 'No end date set.'),
            'state'  => $complete ? 'current' : 'upcoming',
        ];

        return $steps;
    }

    private function volunteeringDetail(bool $accepted, callable $fmt): string
    {
        if (! $accepted) {
            return 'Starts once you accept the offer.';
        }

        if ($this->starts_on && $this->ends_on) {
            return 'This opportunity is active from ' . $fmt($this->starts_on)
                . ' to ' . $fmt($this->ends_on) . '.';
        }

        if ($this->starts_on) {
            return 'This opportunity starts on ' . $fmt($this->starts_on) . '.';
        }

        return 'Dates to be confirmed.';
    }

    // --- CV ---

    /**
     * Disk the uploaded CVs live on. Not web reachable, so the only way to one
     * is VolunteerController::downloadCv, which checks the caller first.
     */
    public const CV_DISK = 'local';

    public function hasCv(): bool
    {
        return (bool) $this->cv_path && Storage::disk(self::CV_DISK)->exists($this->cv_path);
    }

    /** Size in a form a human reads, for the admin list. */
    public function cvSizeForHumans(): string
    {
        $bytes = (int) $this->cv_size;

        return $bytes >= 1048576
            ? round($bytes / 1048576, 1).'MB'
            : max(1, (int) round($bytes / 1024)).'KB';
    }

    /**
     * Take the file with the row.
     *
     * On the deleting event rather than at the call sites, so a CV cannot be
     * orphaned on disk by a delete somebody adds later. These are unsolicited
     * personal documents; leaving them lying around after the application they
     * belonged to has gone is the kind of thing a data audit asks about.
     */
    protected static function booted(): void
    {
        static::deleting(function (self $engagement) {
            if ($engagement->cv_path) {
                Storage::disk(self::CV_DISK)->delete($engagement->cv_path);
            }
        });
    }
}
