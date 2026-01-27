<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Modules\Core\Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Core\Database\Factories\LocationFactory;

class Location extends Model
{
    use HasFactory, HasSpatial;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['type', 'name', 'parent_id', 'coordinates'];

    protected $casts = ['coordinates' => Point::class,];

    protected static function newFactory(): LocationFactory
    {
        return LocationFactory::new();
    }
}
