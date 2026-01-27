<?php


namespace Modules\Organizations\Models;

use Modules\Volunteers\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Modules\Organizations\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organizations\Database\Factories\OpportunityFactory;

use MatanYadaev\EloquentSpatial\Objects\Point;
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
 * - Casts the 'coordinates' attribute to a Point object for easy handling.
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
 * - coordinates: Spatial location stored as a POINT (latitude, longitude)
 *
 * Relationships:
 * - organization(): Each opportunity belongs to one organization.
 * - skills(): Each opportunity may have many related skills.
 *
 * Example usage:
 * $opportunity = Opportunity::create([
 *   'title' => 'Volunteer Program',
 *   'coordinates' => new Point(33.7488, -84.3877), // latitude, longitude
 *   'organization_id' => 1,
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
        'coordinates',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('opportunity')->logOnly(['title', 'description', 'status', 'start_date'])->logOnlyDirty()->setDescriptionForEvent(fn(string $eventName) => "Opportunity has been {$eventName}");
    }

    protected $casts = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->casts['coordinates'] = app()->environment('testing') ? 'string' : Point::class;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function skills()
    {
        return $this->hasMany(OpportunitySkill::class);

    }
}
