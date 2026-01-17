<?php

namespace Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Models\Application;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Applications\Database\Factories\TaskFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'application_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    // protected static function newFactory(): TaskFactory
    // {
    //     // return TaskFactory::new();
    // }

    public function application(): BelongsTo
    {
        return $this->belongsTo (Application::class);
    }

    public function task_hours(): HasMany
    {
        return $this->hasMany (TaskHour::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany (Feedback::class);
    }
}
