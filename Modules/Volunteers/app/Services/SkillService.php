<?php

namespace Modules\Volunteers\Services;

use Illuminate\Support\Str;
use Modules\Volunteers\Models\Skill;

class SkillService
{
    /**
     * Retrieve paginated list of skills ordered by newest first.
     *
     * @return LengthAwarePaginator
     */
    public function list()
    {
        return Skill::query()->latest()->paginate(10);
    }
    /**
     * Create a new skill with generated slug.
     *
     * @param array{name:string} $data
     * @return Skill
     */
    public function create(array $data): Skill
    {
        return Skill::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }
    /**
     * Update an existing skill.
     *
     * If name is updated, slug is regenerated.
     *
     * @param Skill $skill
     * @param array $data
     * @return Skill
     */
    public function update(Skill $skill, array $data): Skill
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $skill->update($data);

        return $skill->refresh();
    }
    /**
     * Delete a skill.
     *
     * @param Skill $skill
     * @return void
     */
    public function delete(Skill $skill): void
    {
        $skill->delete();
    }
}
