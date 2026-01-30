<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\FeedbackFactory;
use Modules\Applications\QueryBuilders\FeedbackQueryBuilder;

/**
 * Feedback Model
 * 
 * Represents performance evaluations and task reviews.
 * Used for volunteer performance assessment and task feedback.
 * 
 * @package Modules\Applications\Models
 * @author Your Name
 * @since 1.0.0
 * 
 * @property int $id
 * @property int $task_id
 * @property string $name_of_org
 * @property string $name_of_vol
 * @property int $rating 1-5 scale
 * @property string $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * 
 * @property-read Task $task
 */
class Feedback extends Model
{
    use SoftDeletes;
    use HasFactory;
    /** @var string Table name */

    protected $table = 'feedbacks';

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
        'name_of_org',
        'name_of_vol',
        'rating',
        'comment',
    ];
    
    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Create a new Eloquent query builder for the model.
     * 
     * @param \Illuminate\Database\Query\Builder $query
     * @return FeedbackQueryBuilder
     */
    public function newEloquentBuilder($query): FeedbackQueryBuilder
    {
        return new FeedbackQueryBuilder($query);
    }
    
   /**
    * Get the task that owns the Feedback
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function task(): BelongsTo
   {
       return $this->belongsTo(Task::class,'task_id');
   }

    /**
     * Check if feedback is a performance evaluation.
     * 
     * @return bool
     */
    public function isPerformanceEvaluation(): bool
    {
        return !empty($this->name_of_org) && !empty($this->name_of_vol);
    }
}
