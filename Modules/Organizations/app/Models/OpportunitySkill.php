<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organizations\Database\Factories\OpportunitySkillFactory;
use Modules\Volunteers\Models\Skill;

/**
 * Model: OpportunitySkill
 *
 * Represents the pivot table linking opportunities to skill IDs.
 * Since the skills module is not yet fully implemented, this model
 * stores `skill_id` values as plain integers without validating
 * against a skills table. Each record connects one opportunity
 * to one skill ID.
 *
 * Relationships:
 * - opportunity(): Belongs to a single Opportunity.
 * - skill(): (commented out) reserved for future integration with
 *   the Skills model once available.
 *
 * Fillable:
 * - opportunity_id
 * - skill_id
 */
class OpportunitySkill extends Model
{
    use HasFactory;

    protected $table = 'opportunity_skill';

    protected static function newFactory()
    {
        return OpportunitySkillFactory::new();
    }

    protected $fillable = [
        'opportunity_id',
        'skill_id',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

}
