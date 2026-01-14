<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Organizations\Database\Factories\OrganizationFactory;

class Organization extends Model
{
    use HasFactory;
    /** * The table associated with the model. * * This model represents the 'organizations' table in the database.
    */
    protected $table = 'organizations';
    /** * The attributes that are mass assignable.
     * * * These fields can be filled directly when creating or updating an organization.
     * * - license_number: Unique license number of the organization *
     *  - type: Type of organization (e.g., NGO, school, charity) *
     *  - bio: Short description or background * - website: Official website (optional) */

    protected $fillable = ['license_number', 'type', 'bio', 'website',];

    /** * Define relationships here. *
     *  * Example: *
     *  An organization may have many volunteers, applications, documents, and evaluations. */
    // public function volunteers()
    // {
    //     return $this->hasMany(\Modules\Volunteers\Entities\Volunteer::class);
    // }

    // public function applications()
    // {
    //     return $this->hasMany(\Modules\Applications\Entities\Application::class);
    // }
    // public function documents()
    // {
    //     return $this->hasMany(\Modules\Documents\Entities\Document::class);
    // }
    // public function evaluations()
    // {
    //     return $this->hasMany(\Modules\Evaluations\Entities\Evaluation::class);
    // }
}
