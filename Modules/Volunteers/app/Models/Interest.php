<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $fillable = ['name', 'slug'];

    public function volunteers()
    {
        return $this->belongsToMany(VolunteerProfile::class, 'volunteer_interests', 'interest_id', 'volunteer_profile_id');
    }
}