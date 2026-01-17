<?php

namespace Modules\Evaluations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Badge extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'badges';
    protected $fillable = [
        'name',
        'criteria',
        'description',
    ];
}
