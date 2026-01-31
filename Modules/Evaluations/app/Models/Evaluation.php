<?php

namespace Modules\Evaluations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Models\User ;
use Modules\Applications\Models\Task ;


class Evaluation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'evaluations';
    protected $fillable = [
        'task_id',
        //'volunteer_id',
        'evaluator_id',
        'improvement',
        'strengths',
        'score',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    /**
      *  Relationships
    */
    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    
    public function volunteer()
    {
        return $this->task->application->volunteer();
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

}
