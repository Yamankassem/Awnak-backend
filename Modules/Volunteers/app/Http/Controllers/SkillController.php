<?php

namespace Modules\Volunteers\Http\Controllers;


use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\Skill;
use Modules\Volunteers\Services\SkillService;
use Modules\Volunteers\Transformers\SkillResource;
use Modules\Volunteers\Http\Requests\Skills\StoreSkillRequest;
use Modules\Volunteers\Http\Requests\Skills\UpdateSkillRequest;

class SkillController extends Controller
{
    public function __construct(
        private SkillService $service
    ) {}

    public function index()
    {
        $skills = $this->service->list();
        return static::paginated(
            paginator: $skills,
            message: 'skills.listed'
        );
    }

    public function store(StoreSkillRequest $request)
    {
        $skill = $this->service->create($request->validated());

        return static::success(
            data: new SkillResource($skill),
            message: 'skill.created'
        );
    }

    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $skill = $this->service->update($skill, $request->validated());

        return static::success(
            data: new SkillResource($skill),
            message: 'skill.updated'
        );
    }

    public function destroy(Skill $skill)
    {
        $this->service->delete($skill);

        return static::success(
            message: 'skill.deleted'
        );
    }
}
