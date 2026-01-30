<?php

namespace Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceMetric extends Model
{
    protected $fillable = [
        'feedback_id',
        'metric_name', 
        'score', 
        'notes'
    ];
    
    const METRICS = [
        'commitment' => 'Commitment',
        'quality' => 'Quality',
        'collaboration' => 'Collaboration',
        'punctuality' => 'Punctuality',
        'initiative' => 'Initiative'
    ];
}