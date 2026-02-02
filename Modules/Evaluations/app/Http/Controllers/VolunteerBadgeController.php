<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Evaluations\Http\Requests\VolunteerBadge\StoreVolunteerBadgeRequest;
use Modules\Evaluations\Http\Requests\VolunteerBadge\UpdateVolunteerBadgeRequest;
use Modules\Evaluations\Models\VolunteerBadge;
use Modules\Evaluations\Services\VolunteerBadgeServices;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class VolunteerBadgeController extends Controller
{
        use AuthorizesRequests;

    protected $volunteerBadgeService;

    public function __construct(VolunteerBadgeServices $volunteerBadgeService)
    {
        $this->volunteerBadgeService = $volunteerBadgeService;
    }
  
    /**
     * List all badges (admin/coordinator sees all, volunteer sees only theirs)
     */
    public function index()
    {   
      try {
            $user = Auth::user();
            $this->authorize('viewAny', VolunteerBadge::class);
            $volunteerBadges = $this->volunteerBadgeService->getAll();
            return static::paginated(
                    paginator: $volunteerBadges,
                    message: 'volunteerBadges.listed'
                );
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode() ?: 500);
            }
    }


    // Show single volunteer badge
    public function show($id)
    {
        try {
            $volunteerBadge = $this->volunteerBadgeService->getVolunteerBadgeById($id);
             $this->authorize('view', $volunteerBadge);
             return static::success(
                data: $volunteerBadge,
                message: 'volunteerBadge.retrieved',
                status: 201
        );
        } catch (\Exception $e) {
            return $this->error('volunteerBadge not found', 404);
        }
    }

    // Award badge to volunteer
    public function store(StoreVolunteerBadgeRequest $request)
    {
         try {
                    $data['awarded_by'] = Auth::id();
                    $this->authorize('create', VolunteerBadge::class);
                    $data = $request->validated();
                    $volunteerBadge = $this->volunteerBadgeService->create($data);
                return static::success(
                                        data:  $volunteerBadge,
                                        message: 'volunteerBadges.created',
                                        status: 201
                                    );
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode() ?: 500);
            }  
    }

    // Update volunteer badge (optional)
    public function update(UpdateVolunteerBadgeRequest $request, VolunteerBadge $volunteerBadge)
    {
        try {
                $this->authorize('update', $volunteerBadge);
                $updated = $this->volunteerBadgeService->update($volunteerBadge, $request->validated());
                  return static::success(
                        data: $updated,
                        message: 'volunteerBadges.updated'
                    );
            } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
            }
    }

    // Remove badge from volunteer
    public function destroy(VolunteerBadge $volunteerBadge)
    {
         try {
            $this->authorize('delete', $volunteerBadge);
            $this->volunteerBadgeService->delete($volunteerBadge);
            return static::success(
                data: null,
                message: 'volunteerBadges.deleted',
                status: 200
            );
        } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
