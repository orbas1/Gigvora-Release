<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'headline',
        'description',
        'website',
        'location',
        'logo_path',
        'cover_path',
    ];

    protected static function booted(): void
    {
        static::creating(function (CompanyProfile $company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name ?: 'company-'.Str::random(6));
            }
        });
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }

    public function owner()
    {
        $model = config('auth.providers.users.model', \App\Models\User::class);
        return $this->belongsTo($model, 'user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'company_id');
    }
}
