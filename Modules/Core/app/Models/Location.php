<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Point;
// use Modules\Core\Database\Factories\LocationFactory;

class Location extends Model
{
    use HasFactory,HasSpatial;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['type', 'name', 'parent_id', 'coordinates'];

    protected $casts = ['coordinates' => Point::class,];

    // protected static function newFactory(): LocationFactory
    // {
    //     // return LocationFactory::new();
    // }
}
