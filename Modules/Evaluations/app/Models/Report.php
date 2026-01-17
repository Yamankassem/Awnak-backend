<?php

namespace Modules\Evaluations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Models\User as ModelsUser;

class Report extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     */
    protected $table = 'reports';

    protected $fillable = [
        'generated_by',
        'report_type',
        'param',
        'generated_at',
        'url',
    ];

    protected $casts = [
        'param' => 'array',
        'generated_at' => 'datetime',
    ];

    /**
      *  Relationships
    */

    // Report Writer
    // public function generator()
    // {
    //     return $this->belongsTo(User::class, 'generated_by');
    // }
}
