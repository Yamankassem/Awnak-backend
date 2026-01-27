<?php

namespace Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArchivedApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'archived_applications';

    protected $fillable = [
        'original_id',
        'opportunity_id',
        'volunteer_id',
        'coordinator_id',
        'assigned_at',
        'description',
        'status',
        'deleted_by',
        'deleted_reason',
        'original_created_at',
        'original_updated_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'original_created_at' => 'datetime',
        'original_updated_at' => 'datetime',
    ];

    
    public function volunteer()
    {
        return $this->belongsTo(\App\Models\User::class, 'volunteer_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(\App\Models\User::class, 'coordinator_id');
    }
    
    public function opportunity()
    {
        return $this->belongsTo(\Modules\Opportunities\Models\Opportunity::class, 'opportunity_id');
    }

    
    public function deletedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    
    public static function archive(Application $application, $deletedBy = null, $reason = null)
    {
        return self::create([
            'original_id' => $application->id,
            'opportunity_id' => $application->opportunity_id,
            'volunteer_id' => $application->volunteer_id,
            'coordinator_id' => $application->coordinator_id,
            'assigned_at' => $application->assigned_at,
            'description' => $application->description,
            'status' => $application->status,
            'deleted_by' => $deletedBy ?? auth()->id(),
            'deleted_reason' => $reason,
            'original_created_at' => $application->created_at,
            'original_updated_at' => $application->updated_at,
        ]);
    }
}