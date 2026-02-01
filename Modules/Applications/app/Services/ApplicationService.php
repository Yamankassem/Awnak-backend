<?php

namespace Modules\Applications\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Applications\Models\Application;
use Modules\Applications\Services\CacheService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Applications\Transformers\ApplicationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Application Service
 *
 * Business logic layer for application operations.
 * Separates business rules from controller logic.
 *
 * @package Modules\Applications\Services
 * @author Your Name
 */
class ApplicationService
{
    /**
     * Create a new controller instance.
     *
     * @param AuditService $auditService
     */

    public function __construct(private CacheService $cacheService)
    {
    }

    /**
     * Get all applications with optional filtering.
     *
     * @param array $filters
     * @return AnonymousResourceCollection
     *
     * @example
     * $service->getAllApplications(['status' => 'pending', 'per_page' => 20]);
     */
    public function getAllApplications(array $filters = []): AnonymousResourceCollection
    {
        $cacheKey = $this->cacheService->generateKey('applications', $filters);

        return $this->cacheService->remember(
            $cacheKey,
            function () use ($filters) {

                $query = Application::query()->with([
                'opportunity:id,title,organization_id',
                'volunteer:id,user_id,full_name',
                'coordinator:id,name,email'
                ]);

                $this->applyFilters($query, $filters);

                $perPage = $filters['per_page'] ?? 15;

                return ApplicationResource::collection($query->paginate($perPage));

            },
            ['applications'],
            300
        );
    }

    /**
     * Get a specific application by ID.
     *
     * @param int $id Application ID
     * @return ApplicationResource
     * @throws ModelNotFoundException
     */
    public function getApplication(int $id): ApplicationResource
    {
        $application = Application::with(['opportunity', 'volunteer', 'coordinator'])->find($id);

        if (!$application) {
            throw new ModelNotFoundException('Application not found');
        }

        return new ApplicationResource($application);
    }

    /**
     * Create a new application.
     *
     * @param array $data Application data
     * @return ApplicationResource
     */
    public function createApplication(array $data): ApplicationResource
    {
        $application = Application::create($data);
        return new ApplicationResource($application);
    }

    /**
     * Update a new application.
     *
     * @param int $id Application ID
     * @param array $data Application data
     * @return ApplicationResource
     */
    public function updateApplication(int $id, array $data): ApplicationResource
    {
        $application = Application::find($id);

        if (!$application) {
            throw new ModelNotFoundException('Application not found');
        }

        $application->update($data);

        Cache::forget('applications_' . md5(serialize([])));
        Cache::forget('dashboard_stats_' . date('Y-m-d'));

        return new ApplicationResource($application->fresh());
    }

    /**
     * Update status a new application.
     *
     * @param int $id Application ID
     * @param string $status Application status
     * @return ApplicationResource
     */
    public function updateApplicationStatus(int $id, string $status): ApplicationResource
    {
        $application = Application::find($id);

        if (!$application) {
            throw new ModelNotFoundException('Application not found');
        }

        $application->update(['status' => $status]);

        Cache::forget('applications_' . md5(serialize([])));
        Cache::forget('dashboard_stats_' . date('Y-m-d'));

        return new ApplicationResource($application->fresh());
    }

    /**
     * Delete a new application.
     *
     * @param int $id Application ID
     * @return ApplicationResource
     */
    public function deleteApplication(int $id): bool
    {
        $application = Application::find($id);

        if (!$application) {
            throw new ModelNotFoundException('Application not found');
        }
        return $application->delete();
    }

    /**
     * Apply filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     *
     * @internal
     */
    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['volunteer_profile_id'])) {
            $query->where('volunteer_profile_id', $filters['volunteer_profile_id']);
        }

        if (isset($filters['opportunity_id'])) {
            $query->where('opportunity_id', $filters['opportunity_id']);
        }

        if (isset($filters['coordinator_id'])) {
            $query->where('coordinator_id', $filters['coordinator_id']);
        }

        if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
    }


    public function getDashboardStats()
    {
        $cacheKey = 'dashboard_stats_' . date('Y-m-d');

        return Cache::remember($cacheKey, 86400, function () {
            return [
                'total_applications' => Application::count(),
                'pending_applications' => Application::where('status', 'pending')->count(),
                'waiting_list' => Application::where('status', 'waiting_list')->count(),
                'approved_applications' => Application::where('status', 'approved')->count(),
                'rejected_applications' => Application::where('status', 'rejected')->count(),
            ];
        });
    }
}
