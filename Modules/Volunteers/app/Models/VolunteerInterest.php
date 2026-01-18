<?php

namespace Modules\Volunteers\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_profile_id',
        'interest_name',
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

    public function scopeByInterest($query, $interestName)
    {
        return $query->where('interest_name', $interestName);
    }
}