<?php

namespace Modules\Applications\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\Application;
use Modules\Applications\Services\AuditService;
use Modules\Applications\Services\CacheService;
use Modules\Applications\Services\ApplicationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Applications\QueryBuilders\ApplicationQueryBuilder;
use Modules\Applications\Http\Requests\ApplicationsRequest\IndexApplicationRequest;
use Modules\Applications\Http\Requests\ApplicationsRequest\StoreApplicationRequest;
use Modules\Applications\Http\Requests\ApplicationsRequest\UpdateApplicationRequest;
use Modules\Applications\Http\Requests\ApplicationsRequest\UpdateStatusApplicationRequest;
use Modules\Applications\Notifications\ApplicationsNotification\NewApplicationNotification;
use Modules\Applications\Notifications\ApplicationsNotification\ApplicationStatusChanged as ApplicationStatusChangedNotification;

/**
 * Application Controller
 *
 * Handles all application-related operations including
 * creation, retrieval, updating, and deletion of volunteer applications.
 *
 * @package Modules\Applications\Http\Controllers
 * @author Your Name
 */
class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ApplicationService $service
     * @param AuditService $auditService
     * @param CacheService $cacheService
     */
    public function __construct(private ApplicationService $service, private AuditService $auditService, private CacheService $cacheService)
    {
    }

    /**
     * Display a listing of applications.
     *
     * @param IndexApplicationRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/applications
     * @permission viewAny applications
     */
    public function index(IndexApplicationRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Application::class);

        $builder = new ApplicationQueryBuilder(Application::query(), $request->user());

        $query = $builder->forCurrentUser();

        $this->applyRequestFilters($query, $request->validated());

        $query->withAllRelations();

        $query->latestFirst();

        $applications = $query->paginate($request->validated('per_page', 15));

        $applications = $this->cacheService->remember(
        'applications_page_' . $request->page . '_status_' . ($request->status ?? 'all'),
        function () use ($request) {
            $builder = new ApplicationQueryBuilder(Application::query(), $request->user());
            $query = $builder->forCurrentUser();
            $this->applyRequestFilters($query, $request->validated());
            $query->withAllRelations();
            $query->latestFirst();
            return $query->paginate($request->validated('per_page', 15));
        },
        ['applications', 'user_' . $request->user()->id, 'page_' . $request->page],180 );

        return $this->paginated($applications, 'application_list_retrieved');
    }

    /**
     * Store a newly created application.
     *
     * @param StoreApplicationRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api POST /api/applications
     * @permission create applications
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $this->authorize('create', Application::class);

        $application = $this->service->createApplication($request->validated());

        $this->auditService->log('created', 'Application', auth()->user(), [
        'application_id' => $application->id,
        'status' => $application->status,
        'volunteer_profile_id' => $application->volunteer_profile_id,
        'opportunity_id' => $application->opportunity_id
        ]);

        $this->sendApplicationCreatedNotifications($application);

        return $this->success($application, 'messages.application_created', 201);
    }

    /**
     * Display the specified application.
     *
     * @param int $id Application ID
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/applications/{id}
     * @permission view applications
     */
    public function show(int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (ModelNotFoundException $e) {
            return $this->error('messages.application_not_found', 404);
        }

        $this->authorize('view', $application);

        return $this->success($application, 'messages.success');
    }

    /**
    * Update the specified application.
    *
    * @param UpdateApplicationRequest $request
    * @param int $id Application ID
    * @return JsonResponse
    * @throws ModelNotFoundException
    * @throws \Illuminate\Auth\Access\AuthorizationException
    *
    * @api PUT /api/applications/{id}
    * @permission update applications
    */
    public function update(UpdateApplicationRequest $request, int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (ModelNotFoundException $e) {
            return $this->error('messages.application_not_found', 404);
        }

        $this->authorize('update', $application);

        $oldStatus = $application->status;
        $application = $this->service->updateApplication($id, $request->validated());

        if ($application->wasChanged('status')) {
            $this->sendApplicationStatusChangedNotifications($application, $oldStatus);
        }

        return $this->success($application, 'messages.updated_success');
    }

    /**
    * Remove the specified application.
    *
    * @param int $id Application ID
    * @return JsonResponse
    * @throws ModelNotFoundException
    * @throws \Illuminate\Auth\Access\AuthorizationException
    *
    * @api DELETE /api/applications/{id}
    * @permission delete applications
    */
    public function destroy(int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (ModelNotFoundException $e) {
            return $this->error('messages.application_not_found', 404);
        }

        $this->authorize('delete', $application);

        $this->auditService->log('deleted', 'Application', auth()->user(), [
        'application_id' => $application->id,
        'status' => $application->status,
        'volunteer_name' => $application->volunteer->name ?? 'Unknown'
        ]);

        $this->service->deleteApplication($id);

        return $this->success(null, 'messages.deleted_success');
    }

    /**
    * Update application status.
    * 
    * @param UpdateStatusApplicationRequest $request
    * @param int $id Application ID
    * @return JsonResponse
    * @throws ModelNotFoundException
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * 
    * @api PATCH /api/applications/{id}/status
    * @permission changeStatus applications
    */
    public function updateStatus(UpdateStatusApplicationRequest $request, int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (ModelNotFoundException $e) {
            return $this->error('messages.application_not_found', 404);
        }

        $this->authorize('changeStatus', $application);

        $oldStatus = $application->status;
        $application = $this->service->updateApplicationStatus($id, $request->validated('status'));

        $this->auditService->log('status_changed', 'Application', auth()->user(), [
        'application_id' => $application->id,
        'old_status' => $oldStatus,
        'new_status' => $application->status,
        'reason' => $request->validated('reason', 'No reason provided')
        ]);

        $this->sendApplicationStatusChangedNotifications($application, $oldStatus, $request->validated('reason'));

        return $this->success($application, 'messages.updated_success');
    }

    /**
    * Get pending applications.
    * 
    * @param Request $request
    * @return JsonResponse
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * 
    * @api GET /api/applications/pending
    * @permission viewAny applications
    */
    public function pending(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Application::class);

        $builder = new ApplicationQueryBuilder(Application::query(), $request->user());

        $applications = $builder->forCurrentUser()
            ->pending()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($applications, 'messages.success');
    }

    /**
    * Get applications on waiting list.
    * 
    * @param Request $request
    * @return JsonResponse
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * 
    * @api GET /api/applications/waiting-list
    * @permission viewAny applications
    */
    public function waitingList(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Application::class);

        $builder = new ApplicationQueryBuilder(Application::query(), $request->user());

        $applications = $builder->forCurrentUser()
            ->waitingList()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($applications, 'messages.success');
    }

    /**
    * Get approved applications.
    * 
    * @param Request $request
    * @return JsonResponse
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * 
    * @api GET /api/applications/approved
    * @permission viewAny applications
    */
    public function approved(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Application::class);

        $builder = new ApplicationQueryBuilder(Application::query(), $request->user());

        $applications = $builder->forCurrentUser()
            ->approved()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($applications, 'messages.success');
    }

    /**
    * Search applications.
    * 
    * @param Request $request
    * @return JsonResponse
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * @throws \Illuminate\Validation\ValidationException
    * 
    * @api GET /api/applications/search
    * @permission viewAny applications
    */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
            'status' => 'sometimes|in:pending,waiting_list,approved,rejected',
        ]);

        $this->authorize('viewAny', Application::class);

        $builder = new ApplicationQueryBuilder(Application::query(), $request->user());

        $query = $builder->forCurrentUser()
            ->searchInDescription($request->q)
            ->withAllRelations()
            ->latestFirst();

        if ($request->has('status')) {
            $query->withStatus($request->status);
        }

        $applications = $query->paginate($request->get('per_page', 15));

        return $this->paginated($applications, 'messages.success');
    }

    /**
      * Apply filters to the query based on request parameters.
      *
      * @param ApplicationQueryBuilder $query
      * @param array $filters
      * @return void
      */
    private function applyRequestFilters(ApplicationQueryBuilder $query, array $filters): void
    {
        if (isset($filters['status'])) {
            $query->withStatus($filters['status']);
        }

        if (isset($filters['opportunity_id'])) {
            $query->forOpportunity($filters['opportunity_id']);
        }

        if (isset($filters['from_date']) && isset($filters['to_date'])) {
            $query->createdBetween($filters['from_date'], $filters['to_date']);
        }

        if (isset($filters['search'])) {
            $query->searchInDescription($filters['search']);
        }
    }

    /**
     * Send notifications when a new application is created.
     *
     * @param Application $application
     * @return void
     */
    private function sendApplicationCreatedNotifications(Application $application): void
    {
        try {
            $application->volunteer->notify(new NewApplicationNotification($application));

            if ($application->coordinator) {
                $application->coordinator->notify(new NewApplicationNotification($application));
            }

            if ($application->opportunity && $application->opportunity->createdBy) {
                $application->opportunity->createdBy->notify(new NewApplicationNotification($application));
            }

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewApplicationNotification($application));
            }

        } catch (Exception $e) {
            Log::error('Failed to send order creation notifications', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
    * Send notifications when application status changes.
    * 
    * @param Application $application
    * @param string $oldStatus Previous status
    * @param string|null $reason Change reason
    * @return void
    */
    private function sendApplicationStatusChangedNotifications(
        Application $application,
        string $oldStatus,
        ?string $reason = null
    ): void {
        try {
            $application->volunteer->notify(
                new ApplicationStatusChangedNotification($application, $oldStatus, $application->status)
            );

            if ($application->coordinator) {
                $application->coordinator->notify(
                    new ApplicationStatusChangedNotification($application, $oldStatus, $application->status)
                );
            }

            Log::info('The order status has been changed', [
                'application_id' => $application->id,
                'from' => $oldStatus,
                'to' => $application->status,
                'by' => auth()->id(),
                'reason' => $reason,
            ]);

        } catch (Exception $e) {
            Log::error('Failed to send status change notifications', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
