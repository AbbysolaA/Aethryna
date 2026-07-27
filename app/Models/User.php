<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user' || $this->role === 'learner';
    }

    /**
     * Check if user is a learner/member
     */
    public function isLearner(): bool
    {
        return $this->role === 'learner' || $this->role === 'user';
    }

    /**
     * Check if user is a mentor.
     *
     * Mentors are volunteers, but they hold their own role value because it
     * gates the learner-facing /mentor area. isVolunteer() below is true for
     * both, so anything that should apply to every contributor asks that.
     */
    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    /**
     * Check if user contributes in any volunteer capacity, mentors included.
     */
    public function isVolunteer(): bool
    {
        return $this->role === 'volunteer' || $this->role === 'mentor';
    }

    /**
     * Check if user is a skills coach (internal staff)
     */
    public function isCoach(): bool
    {
        return $this->role === 'coach';
    }

    // --- Relationships ---

    /**
     * Learners assigned to this mentor
     */
    public function assignedLearners()
    {
        return $this->belongsToMany(User::class, 'mentor_learner_assignments', 'mentor_id', 'learner_id')
            ->withPivot('status', 'assigned_date', 'completion_date', 'notes')
            ->withTimestamps();
    }

    /**
     * Mentor assigned to this learner
     */
    public function assignedMentor()
    {
        return $this->belongsToMany(User::class, 'mentor_learner_assignments', 'learner_id', 'mentor_id')
            ->withPivot('status', 'assigned_date', 'completion_date', 'notes')
            ->withTimestamps();
    }

    /**
     * Learners in this coach's cohort
     */
    public function cohortLearners()
    {
        return $this->hasMany(CoachCohort::class, 'coach_id');
    }

    /**
     * Coach assigned to this learner
     */
    public function assignedCoach()
    {
        return $this->hasOne(CoachCohort::class, 'learner_id');
    }

    /**
     * Mentoring sessions as a mentor
     */
    public function mentoringSessions()
    {
        return $this->hasMany(MentoringSession::class, 'mentor_id');
    }

    /**
     * Mentoring sessions as a learner
     */
    public function learningSessions()
    {
        return $this->hasMany(MentoringSession::class, 'learner_id');
    }

    /**
     * Every volunteer stint this person has held, newest first. A person can
     * hold more than one at a time (mentor and panel facilitator, say), and
     * keeps the record of past ones after they finish.
     */
    public function volunteerEngagements()
    {
        return $this->hasMany(VolunteerEngagement::class)->latest('offer_extended_at');
    }
}
