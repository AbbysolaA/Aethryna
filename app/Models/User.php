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
     * The route this account should land on after signing in.
     *
     * Derived from the role we already hold rather than asked for at the door.
     * A login form that lets someone declare which kind of user they are is
     * asking for something the server knows better, and invites the idea that
     * picking a different option grants different access.
     *
     * Order matters: isVolunteer() is true for mentors too, so mentors are
     * matched first and keep their own area.
     */
    public function homeRoute(): string
    {
        return match (true) {
            $this->isAdmin()            => 'admin.dashboard',
            $this->isSafeguardingLead() => 'admin.safeguarding.index',
            $this->isCoach()            => 'coach.dashboard',
            $this->isMentor()           => 'mentor.dashboard',
            $this->isVolunteer()        => 'volunteer.index',
            default                     => 'dashboard',
        };
    }

    /**
     * Check if user is a skills coach (internal staff)
     */
    public function isCoach(): bool
    {
        return $this->role === 'coach';
    }

    /**
     * Check if user is the safeguarding lead.
     *
     * Deliberately narrow. This role reaches the safeguarding review screens
     * and nothing else, so the person handling concerns about named learners
     * is not also given the user list and the risk register.
     */
    public function isSafeguardingLead(): bool
    {
        return $this->role === 'safeguarding';
    }

    /**
     * Roles that are granted by an admin rather than self-served, and which
     * therefore come through the staff invite flow.
     *
     * @return array<string, string>
     */
    public static function staffRoles(): array
    {
        return [
            'safeguarding' => 'Safeguarding lead',
            'coach'        => 'Skills coach',
            'mentor'       => 'Mentor',
            'admin'        => 'Administrator',
        ];
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
