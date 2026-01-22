<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VolunteerProfile extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'location_id',
        'first_name',
        'last_name',
        'phone',
        'gender',
        'birth_date',
        'bio',
        'experience_years',
        'previous_experience_details',
        'status',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    /*
    |--------------------------------------------------------------------------
    | Spatie Media Library Configuration
    |--------------------------------------------------------------------------
    */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents')
            ->useDisk('public');

        $this->addMediaCollection('certificates')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150);
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Relationships (داخل الموديول فقط)
    |--------------------------------------------------------------------------
    */

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'volunteer_skills', 'volunteer_profile_id', 'skill_id')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'volunteer_interests', 'volunteer_profile_id', 'interest_id')
            ->withTimestamps();
    }

    public function availability()
    {
        return $this->hasMany(related: VolunteerAvailability::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    | User أو Location Models - فقط IDs
    */

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getLocationId(): ?int
    {
        return $this->location_id;
    }
}