<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Applications\Database\Factories\TaskHourFactory;

class Task_hour extends Model
{
    use HasFactory;

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

    // protected static function newFactory(): TaskHourFactory
    // {
    //     // return TaskHourFactory::new();
    // }

    public function task(): BelongsTo
    {
        return $this->belongsTo (Task::class);
    }
}
