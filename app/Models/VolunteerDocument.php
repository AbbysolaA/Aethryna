<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * One file in the volunteer onboarding pack.
 *
 * The file itself lives on the 'local' disk (storage/app/private), so it is
 * never served directly. VolunteerDocumentController::download is the only way
 * out, and it checks the caller first.
 */
class VolunteerDocument extends Model
{
    use HasFactory;

    /** Disk the files live on. Not web-reachable. */
    public const DISK = 'local';

    protected $fillable = [
        'label',
        'note',
        'path',
        'original_name',
        'mime',
        'size',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'size'       => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInPackOrder(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Delete the file from disk as well as the row. Called from the model's
     * deleting event so it cannot be forgotten at a call site.
     */
    protected static function booted(): void
    {
        static::deleting(function (self $document) {
            Storage::disk(self::DISK)->delete($document->path);
        });
    }

    public function url(): string
    {
        return route('volunteer.documents.download', $this);
    }

    public function exists(): bool
    {
        return Storage::disk(self::DISK)->exists($this->path);
    }

    /**
     * Human-readable size for the admin list.
     */
    public function readableSize(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024) . ' KB';
        }

        return $bytes . ' B';
    }

    /**
     * Short type label from the original filename, for the admin list.
     */
    public function extension(): string
    {
        return strtoupper(pathinfo($this->original_name, PATHINFO_EXTENSION)) ?: 'FILE';
    }
}
