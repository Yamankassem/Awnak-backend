<?php

namespace Modules\Applications\Models;

use Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Applications\Database\Factories\CalendarEventFactory;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
        'related_type',
        'related_id',
        'color',
        'is_all_day',
        'location',
        'reminder_minutes',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_all_day' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (!$event->color) {
                $event->color = self::getColorByType($event->type);
            }
        });
    }

    public static function getColorByType($type): string
    {
        $colors = [
            'task' => '#3498db',      
            'meeting' => '#2ecc71',   
            'training' => '#9b59b6',  
            'deadline' => '#e74c3c',  
            'event' => '#f39c12',     
            'reminder' => '#95a5a6',  
        ];

        return $colors[$type] ?? '#3498db';
    }
}