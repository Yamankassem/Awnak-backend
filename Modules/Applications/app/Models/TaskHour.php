<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\TaskHourFactory;

class TaskHour extends Model
{
    use HasFactory;
    protected $table = 'task_hours';

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
     * Get the task that owns the TaskHour
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
