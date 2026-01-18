<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Applications\Database\Factories\ApplicationFactory;

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

    // protected static function newFactory(): ApplicationFactory
    // {
    //     // return ApplicationFactory::new();
    // }

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
        return $this->belongsTo (Volunteer::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo (Coordinator::class);
    }
}
