<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\LanguageFactory;
// use Modules\Volunteers\Database\Factories\LanguageFactory;

class Language extends Model
{
    use HasFactory;

   /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'code'];

    /**
     * Create a new factory instance for the model.
     *
     * @return LanguageFactory
     */
     protected static function newFactory(): LanguageFactory
     {
          return LanguageFactory::new();
     }

     /**
     * Volunteers associated with this language.
     *
     * Includes proficiency level in the pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function volunteers()
    {
        return $this->belongsToMany(
            VolunteerProfile::class,
            'volunteer_languages'
        )->withPivot('level')->withTimestamps();
    }
}
