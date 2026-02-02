<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Evaluations\Http\Requests\Badge\StoreBadgeRequest;
use Modules\Evaluations\Http\Requests\Badge\UpdateBadgeRequest;
use Modules\Evaluations\Services\BadgeServices;
use Modules\Evaluations\Models\Badge;
use Modules\Evaluations\Http\Resources\BadgeResource;
use Modules\Evaluations\Http\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class BadgeController extends Controller
{
    use AuthorizesRequests;

    protected $badgeService;

    public function __construct(BadgeServices $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    // List all badges
    public function index()
    {
        try {
            $badges = $this->badgeService->getAllBadges();
           return static::paginated(
            paginator: $badges,
            message: 'badges.listed'
        );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    // Show single badge
    public function show($id)
    {
        try {
            $badge = $this->badgeService->getBadgeById($id);
             return static::success(
                data: $badge,
                message: 'badges.retrieved',
                status: 201
        );
        } catch (\Exception $e) {
            return $this->error('Badge not found', 404);
        }
    }

    // Create badge
    public function store(StoreBadgeRequest  $request)
    {
        try {
            
             $this->authorize('create', Badge::class);
             $badge = $this->badgeService->createBadge($request->validated());
              return static::success(
                                        data:  $badge,
                                        message: 'badges.created',
                                        status: 201
                                    );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    //  only super admin can update badge
    public function update(UpdateBadgeRequest $request, Badge $badge)
    {
        try {
            $data = $request->validated();
            $this->authorize('update', $badge);
            $updated = $this->badgeService->updateBadge($badge, $data);
            return static::success(
            data: $updated,
            message: 'badges.updated',
            status: 200
        );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    // only super admin can delete badge 
    public function destroy(Badge $badge)
    {
         try {
           $this->authorize('delete', $badge);

            $this->badgeService->deleteBadge($badge);

            return static::success(
                data: null,
                message: 'badges.deleted',
                status: 200
            );
        } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
