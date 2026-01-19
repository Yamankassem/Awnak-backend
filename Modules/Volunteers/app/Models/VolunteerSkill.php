<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_profile_id',
        'skill_name',
        'level',
    ];

    /*
    |--------------------------------------------------------------------------
    | Internal Relationship
    |--------------------------------------------------------------------------
    */

    public function volunteerProfile()
    {
        return $this->belongsTo(VolunteerProfile::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeExpert($query)
    {
        return $query->where('level', 'expert');
    }
}