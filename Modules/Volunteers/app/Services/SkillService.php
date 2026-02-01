<?php

namespace Modules\Volunteers\Services;

use Illuminate\Support\Str;
use Modules\Volunteers\Models\Skill;

class SkillService
{
    public function handle() {}

    public function list()
    {
        return Skill::query()->latest()->paginate(10);
    }

    public function create(array $data): Skill
    {
        return Skill::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    public function update(Skill $skill, array $data): Skill
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $skill->update($data);

        return $skill->refresh();
    }

    public function delete(Skill $skill): void
    {
        $skill->delete();
    }
}
