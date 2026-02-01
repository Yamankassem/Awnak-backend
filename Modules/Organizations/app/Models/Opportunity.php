<?php


namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Organizations\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Model: Opportunity
 *
 * Represents an opportunity record in the database.
 * Each opportunity belongs to one organization and may
 * include spatial coordinates for location-based queries.
 *
 * Features:
 * - Uses HasSpatial trait to enable spatial queries (distance, within, etc.).
 * - Uses LogsActivity trait to track changes (title, description, status, start_date).
 * - Casts coordinates to Point objects for easy handling.
 * - Supports factory creation for testing and seeding.
 *
 * Fillable attributes:
 * - title: Opportunity title
 * - description: Detailed description
 * - type: Type of opportunity (volunteering, training, job, etc.)
 * - start_date: Opportunity start date
 * - end_date: Opportunity end date
 * - status: Current status (approved, rejected, pending)
 * - organization_id: Foreign key linking to organizations table
 * - location_id: Foreign key linking to locations table
 *
 * Relationships:
 * - location(): Belongs to one Location
 * - organization(): Belongs to one Organization
 * - skills(): Has many OpportunitySkill
 * - documents(): Has many Document
 *
 * Example usage:
 * $opportunity = Opportunity::create([
 *   'title' => 'Volunteer Program',
 *   'organization_id' => 1,
 *   'location_id' => 5,
 *   'status' => 'pending',
 * ]);
 */

class Opportunity extends Model
{
    use HasFactory, HasSpatial, LogsActivity;


    protected $fillable = [
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'status',
        'organization_id',
        'location_id',
    ];

     /** * Configure activity log options. * * @return LogOptions */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('opportunity')->logOnly(['title', 'description', 'status', 'start_date'])->logOnlyDirty()->setDescriptionForEvent(fn(string $eventName) => "Opportunity has been {$eventName}");
    }


    protected $casts = [];

     /**
     * Get the location associated with the opportunity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(\Modules\Core\Models\Location::class);
    }

    /** * Get the organization associated with the opportunity. *
     *  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /** * Get the skills associated with the opportunity. *
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function skills()
    {
        return $this->hasMany(OpportunitySkill::class);
    }

    /** * Get the documents associated with the opportunity. *
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *  */
    public function documents()
    {
        return $this->hasMany(\Modules\Organizations\Models\Document::class, 'opportunity_id');
    }
}
