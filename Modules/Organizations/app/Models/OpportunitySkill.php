<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunitySkill extends Model
{
    protected $table = 'opportunity_skill';

    protected $fillable = [
        'opportunity_id',
        'skill_id',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    // public function skill()
    // {
    //     return $this->belongsTo(Skill::class);
    // }
}
