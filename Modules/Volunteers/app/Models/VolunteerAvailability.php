<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerAvailability extends Model
{
    use HasFactory;

    protected $table = 'volunteer_availability';

    protected $fillable = [
        'volunteer_profile_id',
        'day',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
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

    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    public function scopeWeekdays($query)
    {
        return $query->whereIn('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
    }

    public function scopeWeekends($query)
    {
        return $query->whereIn('day', ['saturday', 'sunday']);
    }
}