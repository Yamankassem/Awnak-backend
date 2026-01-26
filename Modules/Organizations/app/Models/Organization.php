<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Organizations\Database\Factories\OrganizationFactory;

 use Modules\Organizations\Database\Factories\OrganizationFactory;
class Organization extends Model
{

    use HasFactory;

    protected static function newFactory() { return OrganizationFactory::new(); }
    /** * The table associated with the model. * * This model represents the 'organizations' table in the database.
    */
    protected $table = 'organizations';

    /** * The attributes that are mass assignable.
     * * * These fields can be filled directly when creating or updating an organization.
     * * - license_number: Unique license number of the organization *
     *  - type: Type of organization (e.g., NGO, school, charity) *
     *  - bio: Short description or background * - website: Official website (optional) */

    protected $fillable = [
        'license_number',
        'type',
        'bio',
        'website',
        'user_id'];

    /** * Define relationships here. *
     *  * Example: *
     *  An organization may have many volunteers, applications, documents, and evaluations. */

    public function volunteers()
    {
        return $this->hasMany(\Modules\Volunteers\Models\VolunteerProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(\Modules\Applications\Models\Application::class);
    }

    // public function documents()
    // {
    //     return $this->hasMany(\Modules\Documents\Entities\Document::class);
    // }

    public function evaluations()
    {
        return $this->hasMany(\Modules\Evaluations\Models\Evaluation::class);
    }

}
