<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pathway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'recommended_for',
        'skills',
        'career_paths',
        'difficulty_level',
        'duration_months',
        'image_path',
        'is_active',
        'is_pilot',
    ];

    protected $casts = [
        'skills' => 'array',
        'career_paths' => 'array',
        'is_active' => 'boolean',
        'is_pilot' => 'boolean',
    ];

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * The tracks a cohort actually runs.
     *
     * Everything else is a direction the assessment can point somebody in
     * rather than a course we teach, and the two are described differently
     * wherever they appear.
     */
    public function scopePilot($query)
    {
        return $query->where('is_pilot', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * A meta description worth having.
     *
     * The stored descriptions run 60 to 95 characters, which leaves most of
     * the ~155 a search result will show sitting empty. Career paths are the
     * part a person searching for a course actually wants, so they fill the
     * rest — trimmed on a whole entry rather than mid-word.
     */
    public function metaDescription(): string
    {
        $base = trim((string) $this->description);

        $careers = collect($this->career_paths ?? [])->filter()->values();

        if ($careers->isNotEmpty() && mb_strlen($base) < 120) {
            $lead = ' Leads to work as ';
            $room = 155 - mb_strlen($base) - mb_strlen($lead) - 1;

            $fit = collect();
            foreach ($careers as $career) {
                $candidate = $fit->push($career)->implode(', ');
                if (mb_strlen($candidate) > $room) {
                    $fit->pop();
                    break;
                }
            }

            if ($fit->isNotEmpty()) {
                $base .= $lead . $fit->implode(', ') . '.';
            }
        }

        return $base;
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
