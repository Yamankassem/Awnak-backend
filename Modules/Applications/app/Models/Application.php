<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
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

     protected static function newFactory(): ApplicationFactory
     {
          return ApplicationFactory::new();
     }

    public function tasks(): HasMany
    {
        return $this->hasMany (Task::class);
    }
}
