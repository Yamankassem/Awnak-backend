<?php

namespace Modules\Applications\Models;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\FeedbackFactory;

class Feedback extends Model
{
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

     protected static function newFactory(): FeedbackFactory
     {
          return FeedbackFactory::new();
     }

    public function task(): BelongsTo
    {
        return $this->belongsTo (Task::class);
    }
}
