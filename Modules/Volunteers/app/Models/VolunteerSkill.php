<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\VolunteerSkillFactory;

class VolunteerSkill extends Model
{
    use HasFactory;

     protected static function newFactory()
    {
        return VolunteerSkillFactory::new();
    }

    protected $fillable = [
        'volunteer_profile_id',
        'skill_id',
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

    public function skill()
    {
        return $this->belongsTo(Skill::class);
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
