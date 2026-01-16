<?php

namespace Modules\Evaluations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Evaluations\Models\Badge as ModelsBadge;
use Modules\Core\Models\User as ModelsUser;


class VolunteerBadge extends Model
{
     use HasFactory;
     /**
     * The attributes that are mass assignable.
     */
    protected $table = 'volunteer_badges';
    protected $fillable = [
        'volunteer_id',
        'badge_id',
        'awarded_by',
        'awarded_at',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    /**
     *  Relationships
     */

    // The volunteer who received the badge
    // public function volunteer()
    // {
    //     return $this->belongsTo(User::class, 'volunteer_id');
    // }
    // // badge
    // public function badge()
    // {
    //     return $this->belongsTo(ModelsBadge::class);
    // }
    // // badge giver
    // public function awardedBy()
    // {
    //     return $this->belongsTo(User::class, 'awarded_by');
    // }
}
