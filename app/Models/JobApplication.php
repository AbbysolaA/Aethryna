<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * One person applying for one paid role.
 *
 * See the migration for why this is not a volunteer_engagement. The lifecycle
 * here is a hiring one: new, shortlisted, then hired or declined, driven from
 * the admin screen rather than by offer tokens.
 */
class JobApplication extends Model
{
    /**
     * Disk the CV lives on. storage/app/private, which the web server does not
     * serve; the admin download route is the only way to the file.
     */
    public const CV_DISK = 'local';

    public const STATUSES = ['new', 'shortlisted', 'hired', 'declined'];

    protected $fillable = [
        'volunteer_role_id',
        'name',
        'email',
        'phone',
        'cover_note',
        'portfolio_url',
        'cv_path',
        'cv_original_name',
        'cv_mime',
        'cv_size',
        'status',
        'consented_at',
    ];

    protected function casts(): array
    {
        return [
            'consented_at' => 'datetime',
            'cv_size'      => 'integer',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(VolunteerRole::class, 'volunteer_role_id');
    }

    /** Applications an admin has not looked at yet. */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    /** Still in the running: not yet declined or hired. */
    public function scopeInFlight($query)
    {
        return $query->whereIn('status', ['new', 'shortlisted']);
    }

    public function hasCv(): bool
    {
        return (bool) $this->cv_path && Storage::disk(self::CV_DISK)->exists($this->cv_path);
    }

    public function cvSizeForHumans(): string
    {
        $bytes = (int) $this->cv_size;

        return $bytes >= 1048576
            ? round($bytes / 1048576, 1).'MB'
            : max(1, (int) round($bytes / 1024)).'KB';
    }

    /**
     * The file goes with the row. On the deleting event so a delete added
     * later cannot orphan a stranger's CV on disk.
     */
    protected static function booted(): void
    {
        static::deleting(function (self $application) {
            if ($application->cv_path) {
                Storage::disk(self::CV_DISK)->delete($application->cv_path);
            }
        });
    }
}
