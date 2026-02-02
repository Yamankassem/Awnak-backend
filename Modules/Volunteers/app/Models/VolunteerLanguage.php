<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\VolunteerLanguageFactory;
// use Modules\Volunteers\Database\Factories\VolunteerLanguageFactory;

class VolunteerLanguage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'volunteer_profile_id',
        'language_id',
        'level',
    ];
    /**
     * Factory for testing/seeding.
     *
     * @return VolunteerLanguageFactory
     */
    protected static function newFactory(): VolunteerLanguageFactory
    {
        return VolunteerLanguageFactory::new();
    }
    /**
     * Parent volunteer profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function volunteerProfile()
    {
        return $this->belongsTo(VolunteerProfile::class);
    }
    /**
     * Associated language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
