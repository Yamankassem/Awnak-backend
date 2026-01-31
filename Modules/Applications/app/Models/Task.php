<?php

namespace Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Models\Application;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\TaskFactory;

/**
 * Task Model
 * 
 * Represents a volunteer task assigned as part of an application.
 * Tasks track work items, status, due dates, hours, and feedback.
 * 
 * @package Modules\Applications\Models
 * @author Your Name
 * @since 1.0.0
 * 
 * @property int $id
 * @property int $application_id
 * @property string $title
 * @property string $description
 * @property string $status preparation|active|complete|cancelled
 * @property \Carbon\Carbon $due_date
 * @property \Carbon\Carbon|null $completed_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * 
 * @property-read Application $application
 * @property-read \Illuminate\Database\Eloquent\Collection|TaskHour[] $taskHours
 * @property-read \Illuminate\Database\Eloquent\Collection|Feedback[] $feedbacks
 */
class Task extends Model
{
    use SoftDeletes;
    use HasFactory;
    /** @var string Table name */

    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */

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
    
    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
    ];


    protected static function newFactory()
    {
        return TaskFactory::new();
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
