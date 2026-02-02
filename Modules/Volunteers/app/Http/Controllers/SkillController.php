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
    public function __construct(private SkillService $service ) {}
    /**
     * Display paginated list of skills.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $skills = $this->service->list();
        return static::paginated(
            paginator: $skills,
            message: 'skills.listed'
        );
    }
    /**
     * Create a new skill.
     *
     * @param StoreSkillRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSkillRequest $request)
    {
        $skill = $this->service->create($request->validated());

        return static::success(
            data: new SkillResource($skill),
            message: 'skill.created'
        );
    }
    /**
     * Update an existing skill.
     *
     * @param UpdateSkillRequest $request
     * @param Skill $skill
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $skill = $this->service->update($skill, $request->validated());

        return static::success(
            data: new SkillResource($skill),
            message: 'skill.updated'
        );
    }
    /**
     * Delete a skill.
     *
     * @param Skill $skill
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Skill $skill)
    {
        $this->service->delete($skill);

        return static::success(
            message: 'skill.deleted'
        );
    }
}
