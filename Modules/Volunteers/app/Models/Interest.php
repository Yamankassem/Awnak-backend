<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\InterestFactory;

class Interest extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return InterestFactory
     */
    protected static function newFactory()
    {
        return InterestFactory::new();
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Volunteers associated with this interest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function volunteers()
    {
        return $this->belongsToMany(VolunteerProfile::class, 'volunteer_interests', 'interest_id', 'volunteer_profile_id');
    }
}
