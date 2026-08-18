<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'contact_name',
        'contact_email',
        'resume_token',
        'unsubscribe_token',
        'status',
        'responses',
        'scores',
        'started_at',
        'completed_at',
        'results_emailed_at',
        'reminder_sent_at',
        'reminders_opted_out_at',
    ];

    protected $casts = [
        'responses' => 'array',
        'scores' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'results_emailed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'reminders_opted_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function results()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Assessments that were started and never finished, whatever the row says.
     *
     * Rows are only stamped 'abandoned' once the tidy in assessments:remind has
     * run over them, so status alone would miss everything abandoned since the
     * last run. Anything not completed is unfinished.
     */
    public function scopeUnfinished($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    /**
     * How many questions were actually answered.
     *
     * responses is a map keyed by question number, so its size is the count.
     * Used for the drop-off breakdown in admin and to decide whether an
     * abandoned assessment is worth a reminder — someone who answered nothing
     * bounced off the landing page and has no progress to come back to.
     */
    public function answeredCount(): int
    {
        return is_array($this->responses) ? count($this->responses) : 0;
    }

    /**
     * Where results and reminders should go.
     *
     * An account email wins over one typed into the assessment: it is the one
     * the person has confirmed and can change. contact_email is the fallback
     * that makes anonymous assessments reachable at all.
     */
    public function recipientEmail(): ?string
    {
        return $this->user?->email ?: ($this->contact_email ?: null);
    }

    public function recipientName(): ?string
    {
        return $this->user?->name ?: ($this->contact_name ?: null);
    }

    public function recipientFirstName(): string
    {
        $name = trim((string) $this->recipientName());

        return $name === '' ? 'there' : explode(' ', $name)[0];
    }

    /**
     * Mint the resume token if this assessment does not have one yet.
     *
     * Kept stable once issued so a link already sitting in someone's inbox
     * keeps working when a reminder is sent later.
     */
    public function ensureResumeToken(): string
    {
        if (! $this->resume_token) {
            $this->forceFill(['resume_token' => Str::random(48)])->save();
        }

        return $this->resume_token;
    }

    public function resumeUrl(): string
    {
        return route('assessment.resume', ['token' => $this->ensureResumeToken()]);
    }

    /**
     * The opt-out secret. Distinct from the resume token on purpose — see the
     * migration that adds it.
     */
    public function ensureUnsubscribeToken(): string
    {
        if (! $this->unsubscribe_token) {
            $this->forceFill(['unsubscribe_token' => Str::random(48)])->save();
        }

        return $this->unsubscribe_token;
    }

    public function unsubscribeUrl(): string
    {
        return route('assessment.unsubscribe', ['token' => $this->ensureUnsubscribeToken()]);
    }

    public function hasOptedOutOfReminders(): bool
    {
        return $this->reminders_opted_out_at !== null;
    }
}
