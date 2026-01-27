<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Modules\Applications\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\FeedbackFactory;

class Feedback extends Model
{
    use SoftDeletes, Auditable;
    use HasFactory;
    protected $table = 'feedbacks';

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
    * Get the task that owns the Feedback
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function task(): BelongsTo
   {
       return $this->belongsTo(Task::class,'task_id');
   }
}
