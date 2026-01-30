<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\InterestFactory;

class Interest extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return InterestFactory::new();
    }

    protected $fillable = ['name', 'slug'];

    public function volunteers()
    {
        return $this->belongsToMany(VolunteerProfile::class, 'volunteer_interests', 'interest_id', 'volunteer_profile_id');
    }
}
