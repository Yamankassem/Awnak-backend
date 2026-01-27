<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\SkillFactory;

class Skill extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return SkillFactory::new();
    }
    
    protected $fillable = ['name', 'slug'];

    public function volunteers()
    {
        return $this->belongsToMany(VolunteerProfile::class, 'volunteer_skills', 'skill_id', 'volunteer_profile_id');
    }
}
