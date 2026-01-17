<?php

namespace Modules\Evaluations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Models\User as ModelsUser;
use Modules\Applications\Models\Task as ModelsTask;


class Evaluation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'evaluations';
    protected $fillable = [
        'task_id',
        'volunteer_id',
        'evaluator_id',
        'evaluated_at',
        'improvement',
        'strengths',
        'score',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
        'score' => 'float',
    ];

    /**
      *  Relationships
    */
    
    // public function task()
    // {
    //     return $this->belongsTo(Task::class);
    // }

    // public function volunteer()
    // {
    //     return $this->belongsTo(User::class, 'volunteer_id');
    // }

    // public function evaluator()
    // {
    //     return $this->belongsTo(User::class, 'evaluator_id');
    // }

}
