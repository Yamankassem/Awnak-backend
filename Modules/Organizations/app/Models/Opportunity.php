<?php


namespace Modules\Organizations\Models;

use Modules\Volunteers\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Modules\Organizations\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organizations\Database\Factories\OpportunityFactory;


/**
 * Model: Opportunity
 *
 * Represents an opportunity record in the database.
 * Each opportunity belongs to one organization.
 */
class Opportunity extends Model
{
    use HasFactory;
    protected static function newFactory()
    {
        return OpportunityFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be filled using create() or update() methods.
     */
    protected $fillable = [
        'title',          // Opportunity title
        'description',    // Detailed description of the opportunity
        'type',           // Type of opportunity (volunteering, training, job, etc.)
        'start_date',     // Opportunity start date
        'end_date',       // Opportunity end date
        'organization_id' // Foreign key linking to organizations table
    ];

    /**
     * Define the relationship with the Organization model.
     *
     * Each opportunity belongs to one organization.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Opportunity has many OppottunnitySkill
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'opportunity_skill' );
    }
}
