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

     protected static function newFactory(): VolunteerLanguageFactory
     {
          return VolunteerLanguageFactory::new();
     }

    public function volunteerProfile()
    {
        return $this->belongsTo(VolunteerProfile::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
