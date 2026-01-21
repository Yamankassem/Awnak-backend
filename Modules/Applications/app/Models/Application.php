<?php

namespace Modules\Applications\Models;

use Modules\Core\Models\User;
use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Modules\Volunteers\Models\VolunteerProfile;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\ApplicationFactory;
// use Modules\Applications\Database\Factories\ApplicationFactory;

class Application extends Model
{
    use HasFactory;
    protected $table = 'applications';
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
       'opportunity_id',
       'volunteer_id',
       'coordinator_id',
       'assigned_at',
       'description',
    ];
     

   /**
    * Get all of the tasks for the Application
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function tasks(): HasMany
   {
       return $this->hasMany(Task::class);
   }

        
    /**
     * Get the opportunity that owns the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class, 'opportunity_id');
    }


    /**
     * Get the volunteer that owns the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function volunteerProfile(): BelongsTo
    {
        return $this->belongsTo(VolunteerProfile::class, 'volunteer_id');
    }

    

     /**
     * Get the user that owns the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }
}
