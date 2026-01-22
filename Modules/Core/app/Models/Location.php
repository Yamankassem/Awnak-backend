<?php

namespace Modules\Core\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Core\Database\Factories\LocationFactory;

class Location extends Model
{
    use HasFactory, HasSpatial, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['type', 'name', 'parent_id', 'coordinates'];

    protected $casts = ['coordinates' => Point::class,];

    // protected static function newFactory(): LocationFactory
    // {
    //     // return LocationFactory::new();
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('audit')
            ->logOnlyDirty()
            ->logFillable();
    }
}
