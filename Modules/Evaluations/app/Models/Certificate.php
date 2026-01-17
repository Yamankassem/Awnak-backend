<?php

namespace Modules\Evaluations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Models\Task as ModelsTask;


class Certificate extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'certificates';
    protected $fillable = [
        'task_id',
        'hours',
        'context',
        'issued_at',
    ];
    protected $casts = [
        'issued_at' => 'datetime',
        'hours' => 'integer',
    ];

    /**
      *  Relationships
    */

    // public function task()
    // {
    //     return $this->belongsTo(Task::class);
    // }
}
