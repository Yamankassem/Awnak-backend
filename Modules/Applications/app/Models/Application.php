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

class Application extends Model
{
    use HasFactory;
    
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
    

    public function tasks(): HasMany
    {
        return $this->hasMany (Task::class);
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo (Opportunity::class);
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo (VolunteerProfile::class, 'volunteer_id');
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo (User::class, 'coordinator_id');
    }
}
