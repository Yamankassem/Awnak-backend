<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'slug'];

    public function volunteers()
    {
        return $this->belongsToMany(VolunteerProfile::class, 'volunteer_skills', 'skill_id', 'volunteer_profile_id');
    }
}