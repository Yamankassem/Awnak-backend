<?php

namespace Modules\Applications\Models;

use Modules\Core\Models\User;
use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Modules\Organizations\Models\Opportunity;
use Modules\Volunteers\Models\VolunteerProfile;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\ApplicationFactory;

/**
 * Application Model
 * 
 * Represents a volunteer application for an opportunity
 * 
 * @package Modules\Applications\Models
 * @author Your Name
 * @since 1.0.0
 * 
 * @property int $id
 * @property int $opportunity_id
 * @property int $volunteer_id
 * @property int $coordinator_id
 * @property \Carbon\Carbon|null $assigned_at
 * @property string $description
 * @property string $status pending|approved|rejected|waiting_list
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * 
 * @property-read Opportunity $opportunity
 * @property-read VolunteerProfile $volunteer
 * @property-read User $coordinator
 * @property-read \Illuminate\Database\Eloquent\Collection|Task[] $tasks
 */

class Application extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */
    protected $fillable = [
       'opportunity_id',
       'volunteer_id',
       'coordinator_id',
       'assigned_at',
       'description',
       'status',
    ];

    protected static function newFactory()
    {
        return ApplicationFactory::new();
    }
    
    /**
    * Get all of the tasks for the application
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function tasks(): HasMany
    {
        return $this->hasMany (Task::class);
    }


    /**
    * Get the opportunity that owns the application
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo (Opportunity::class);
    }


    /**
    * Get the volunteer that owns the application
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo (VolunteerProfile::class, 'volunteer_id');
    }

    
    /**
    * Get the coordinator that owns the application
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo (User::class, 'coordinator_id');
    }
}
