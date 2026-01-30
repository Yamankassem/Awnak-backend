<?php

namespace Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Traits\Auditable;
use Modules\Applications\Traits\HasStatus;
use Modules\Applications\Models\Application;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\TaskFactory;

class Task extends Model
{
    use SoftDeletes, Auditable, HasStatus;
    use HasFactory;
    protected $table = 'tasks';

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
    
    protected $casts = [
        'due_date' => 'date',
    ];

   
    public function getAllowedStatuses(): array
    {
        return [
         'active', 'complete'
        ];
    }

    /**
     * Get the application that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class,'application_id');
    }

    /**
     * Get all of the taskHours for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskHours(): HasMany
    {
        return $this->hasMany(TaskHour::class);
    }

    /**
     * Get all of the feedbacks for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }
}
