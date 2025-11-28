<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'description',
        'location',
        'workplace_type',
        'employment_type',
        'salary_min',
        'salary_max',
        'currency',
        'status',
        'published_at',
        'expires_at',
        'is_featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected $appends = [
        'salary_label',
        'tag_list',
    ];

    protected static function booted(): void
    {
        static::creating(function (Job $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(6);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(JobBookmark::class);
    }

    public function screeningQuestions()
    {
        return $this->hasMany(ScreeningQuestion::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function getSalaryLabelAttribute(): string
    {
        if ($this->salary_min && $this->salary_max) {
            return sprintf('%s%s - %s%s', $this->currency ?: '$', number_format((float) $this->salary_min, 0), $this->currency ?: '$', number_format((float) $this->salary_max, 0));
        }

        if ($this->salary_min) {
            return sprintf('From %s%s', $this->currency ?: '$', number_format((float) $this->salary_min, 0));
        }

        if ($this->salary_max) {
            return sprintf('Up to %s%s', $this->currency ?: '$', number_format((float) $this->salary_max, 0));
        }

        return 'Competitive';
    }

    public function getTagListAttribute(): array
    {
        return array_filter([
            $this->workplace_type,
            $this->employment_type,
            $this->is_featured ? 'Featured' : null,
        ]);
    }
}
