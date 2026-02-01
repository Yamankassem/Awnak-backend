<?php

namespace Modules\Volunteers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteers\Database\Factories\LanguageFactory;
// use Modules\Volunteers\Database\Factories\LanguageFactory;

class language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'code'];

     protected static function newFactory(): LanguageFactory
     {
          return LanguageFactory::new();
     }

    public function volunteers()
    {
        return $this->belongsToMany(
            VolunteerProfile::class,
            'volunteer_languages'
        )->withPivot('level')->withTimestamps();
    }
}
