<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\VolunteerInterestFactory;

class VolunteerInterest extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return VolunteerInterestFactory::new();
    }

    protected $fillable = [
        'volunteer_profile_id',
        'interest_id',
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

    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }
}
