<?php

namespace Modules\Applications\Http\Controllers\ApplicationsController;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\Application;
use Modules\Applications\Models\ArchivedApplication;
use Modules\Applications\Services\ApplicationsService\ApplicationService;
use Modules\Applications\Http\Requests\ApplicationsRequest\StoreApplicationRequest;
use Modules\Applications\Http\Requests\ApplicationsRequest\UpdateApplicationRequest;
use Modules\Applications\Notifications\ApplicationsNotification\NewApplicationNotification;
use Modules\Applications\Http\Requests\ApplicationsRequest\IndexApplicationRequest;
use Modules\Applications\Notifications\ApplicationsNotification\ApplicationStatusChanged as ApplicationStatusChangedNotification;

class ApplicationController extends Controller
{
    public function __construct(private ApplicationService $service) {}

    
    public function index(IndexApplicationRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Application::class);
        $applications = $this->service->getAllApplications($request->validated());
        
        // تحقق إذا كان البيانات متضمنة paginator
        if ($applications instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return $this->paginated($applications, 'application_list_retrieved');
        }
        
        return $this->success($applications, 'application_list_retrieved');
    }

    
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $this->authorize('create', Application::class);
        
        $application = $this->service->createApplication($request->validated());
        
        $this->sendApplicationCreatedNotifications($application);
        
        return $this->success($application, 'messages.application_created', 201);
    }

    
    public function show(int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.application_not_found', 404);
        }
        
        $this->authorize('view', $application);
        
        return $this->success($application, 'messages.success');
    }

    
    public function update(UpdateApplicationRequest $request, int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
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

    public function destroy(int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.application_not_found', 404);
        }
        
        $this->authorize('delete', $application);
        
        $this->archiveApplication($application);
        
        $this->service->deleteApplication($id);
        
        return $this->success(null, 'messages.deleted_success');
    }

    
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $application = $this->service->getApplication($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.application_not_found', 404);
        }
        
        $this->authorize('changeStatus', $application);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string|max:500',
        ]);
        
        $oldStatus = $application->status;
        $application = $this->service->updateApplicationStatus($id, $validated['status']);
        
        $this->sendApplicationStatusChangedNotifications($application, $oldStatus, $validated['reason'] ?? null);
        
        return $this->success($application, 'messages.updated_success');
    }
    
    
    private function sendApplicationCreatedNotifications(Application $application): void
    {
        try {
            $application->volunteer->notify(new NewApplicationNotification($application, 'volunteer'));
            
            if ($application->coordinator) {
                $application->coordinator->notify(new NewApplicationNotification($application, 'coordinator'));
            }
            
            if ($application->opportunity && $application->opportunity->createdBy) {
                $application->opportunity->createdBy->notify(new NewApplicationNotification($application, 'opportunity_manager'));
            }
            
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewApplicationNotification($application, 'admin'));
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to send application created notifications', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    
    private function sendApplicationStatusChangedNotifications(
        Application $application, 
        string $oldStatus, 
        ?string $reason = null
    ): void {
        try {
            $application->volunteer->notify(
                new ApplicationStatusChangedNotification($application, $oldStatus, $application->status, $reason)
            );
            
            if ($application->coordinator) {
                $application->coordinator->notify(
                    new ApplicationStatusChangedNotification($application, $oldStatus, $application->status, $reason)
                );
            }
            
            \Log::info('Application status changed', [
                'application_id' => $application->id,
                'from' => $oldStatus,
                'to' => $application->status,
                'by' => auth()->id(),
                'reason' => $reason,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send status change notifications', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
   
    private function archiveApplication(Application $application): void
    {
        try {
            ArchivedApplication::archive(
                $application, 
                auth()->id(),
                'Deleted by user action'
            );
            
            \Log::info('Application archived before deletion', [
                'application_id' => $application->id,
                'archived_by' => auth()->id(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to archive application', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    
    public function trashed(Request $request): JsonResponse
    {
        $this->authorize('viewTrashed', Application::class);
        
        $perPage = $request->get('per_page', 20);
        
        $applications = Application::onlyTrashed()
            ->with(['opportunity', 'volunteer', 'coordinator'])
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);
            
        return $this->paginated($applications, 'messages.trashed_applications_retrieved');
    }
    
   
    public function restore(int $id): JsonResponse
    {
        $application = Application::onlyTrashed()->findOrFail($id);
        
        $this->authorize('restore', $application);
        
        $application->restore();
        
        \Log::info('Application restored', [
            'application_id' => $application->id,
            'restored_by' => auth()->id(),
        ]);
        
        return $this->success($application, 'messages.application_restored');
    }
   
    public function forceDelete(int $id): JsonResponse
    {
        $application = Application::onlyTrashed()->findOrFail($id);
        
        $this->authorize('forceDelete', $application);
        
        $application->forceDelete();
        
        \Log::warning('Application permanently deleted', [
            'application_id' => $application->id,
            'deleted_by' => auth()->id(),
        ]);
        
        return $this->success(null, 'messages.application_permanently_deleted');
    }
}