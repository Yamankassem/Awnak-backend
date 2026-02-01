<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerSkill;
use Modules\Volunteers\Services\VolunteerSkillService;
use Modules\Volunteers\Http\Requests\VolunteerSkill\StoreVolunteerSkillRequest;
use Modules\Volunteers\Http\Requests\VolunteerSkill\UpdateVolunteerSkillRequest;

class VolunteerSkillController extends Controller
{

    public function __construct(private VolunteerSkillService $service) {}

    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        $skills = $this->service->list($profile);

        return static::success(data: $skills);
    }

    public function store(StoreVolunteerSkillRequest $request)
    {
        $this->authorize('create', VolunteerSkill::class);

        $profile = $request->user()->volunteerProfile;

        $skill = $this->service->create(
            $profile,
            $request->validated(),
            $request->user()
        );

        return static::success(
            data: $skill,
            message: 'skill.added',
            status: 201
        );
    }

    public function update(UpdateVolunteerSkillRequest $request, VolunteerSkill $volunteerSkill)
    {
        $this->authorize('update', $volunteerSkill);

        $skill = $this->service->update(
            $volunteerSkill,
            $request->validated(),
            $request->user()
        );

        return static::success(
            data: $skill,
            message: 'skill.updated'
        );
    }

    public function destroy(VolunteerSkill $volunteerSkill,Request $request)
    {
        $this->authorize('delete', $volunteerSkill);

        $this->service->delete($volunteerSkill,$request->user());

        return static::success(message: 'skill.deleted');
    }
}
