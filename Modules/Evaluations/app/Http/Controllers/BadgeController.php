<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Evaluations\Http\Requests\Badge\StoreBadgeRequest;
use Modules\Evaluations\Http\Requests\Badge\UpdateBadgeRequest;
use Modules\Evaluations\Services\BadgeServices;
use Modules\Evaluations\Models\Badge;
use Modules\Evaluations\Http\Resources\BadgeResource;
use Modules\Evaluations\Http\Traits\ApiResponse;

class BadgeController extends Controller
{
    use ApiResponse;

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
            return $this->successResponse(
                BadgeResource::collection($badges),
                'Badges retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    // Show single badge
    public function show($id)
    {
        try {
            $badge = $this->badgeService->getBadgeById($id);
            return $this->successResponse(
                new BadgeResource($badge),
                'Badge retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Badge not found', 404);
        }
    }

    // Create badge
    public function store(StoreBadgeRequest  $request)
    {
        try {
            $data = $request->validated();
            $badge = $this->badgeService->createBadge($data);
            return $this->successResponse(
                new BadgeResource($badge),
                'Badge created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    // Update badge
    public function update(UpdateBadgeRequest $request, Badge $badge)
    {
        try {
            $data = $request->validated();
            $updated = $this->badgeService->updateBadge($badge, $data);
            return $this->successResponse(
                new BadgeResource($updated),
                'Badge updated successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    // Delete badge
    public function destroy(Badge $badge)
    {
        try {
            $this->badgeService->deleteBadge($badge);
            return $this->successResponse(
                null,
                'Badge deleted successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
