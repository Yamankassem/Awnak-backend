<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organizations\Database\Factories\OpportunitySkillFactory;


class OpportunitySkill extends Model
{
    use HasFactory;

    protected $table = 'opportunity_skill';

    protected static function newFactory()
    { return OpportunitySkillFactory::new(); }



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
