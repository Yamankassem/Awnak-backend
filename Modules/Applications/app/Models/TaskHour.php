<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Applications\Policies\TaskHourPolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * TaskHour Model
 * 
 * Represents hours logged by volunteers for specific tasks.
 * Tracks work periods, hours, and notes.
 * 
 * @package Modules\Applications\Models
 * @author Your Name
 * @since 1.0.0
 * 
 * @property int $id
 * @property int $task_id
 * @property int $hours
 * @property \Carbon\Carbon $started_date
 * @property \Carbon\Carbon $ended_date
 * @property string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * 
 * @property-read Task $task
 */
class TaskHour extends Model
{
    use SoftDeletes;
    use HasFactory;
    /** @var string Table name */

    protected $table = 'task_hours';

     /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id',
        'hours',
        'started_date',
        'ended_date',
        'note',
    ];
    
    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'started_date' => 'date',
        'ended_date' => 'date',
    ];
   
    protected $policies = [
    TaskHour::class => TaskHourPolicy::class,
    ];

    /**
     * Get the task that owns the TaskHour
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
