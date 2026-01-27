<?php

namespace Models\Applications\Services\ApplicationsService; 

use Modules\Volunteers\Models\Skill;
use Illuminate\Support\Facades\Cache;
use Modules\Applications\Models\Task;
use Modules\Applications\Models\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Applications\Transformers\ApplicationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationService 
{
    public function __construct() {}

    public function getAllApplications(array $filters = []): AnonymousResourceCollection
    {
        $query = Application::with(['opportunity', 'volunteer', 'coordinator']);

        $this->applyFilters($query, $filters);
        
        $perPage = $filters['per_page']?? 15;
        
        $applications = $query->paginate($perPage);
        
        return ApplicationResource::collection($applications);
    }

    public function getApplication(int $id): ApplicationResource
    {
        $application = Application::with(['opportunity', 'volunteer', 'coordinator'])->find($id);

        if (!$application)
        {   
            throw new ModelNotFoundException('Application not found');
        }

        return new ApplicationResource ($application);
    }

    public function createApplication(array $data): ApplicationResource
    {
        $application = Application::create($data);
        return new ApplicationResource ($application);
    }

    public function updateApplication(int $id, array $data): ApplicationResource
    {
        $application = Application::find($id);

        if (!$application)
        {   
            throw new ModelNotFoundException('Application not found');
        }
        
        $application->update($data);

        Cache::forget('applications_' . md5(serialize([])));
        Cache::forget('dashboard_stats_' . date('Y-m-d'));

        return new ApplicationResource ($application->fresh());
    }

    public function deleteApplication(int $id): bool
    {
        $application = Application::find($id);
        
        if (!$application)
        {   
            throw new ModelNotFoundException('Application not found');
        }
        return $application->delete();
    }

    public function updateApplicationStatus(int $id, string $status): ApplicationResource
    {
       $application = Application::find($id);

        if (!$application)
        {   
            throw new ModelNotFoundException('Application not found');
        }

         $application->update(['status' => $status]);

        return new ApplicationResource ($application->fresh());
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['volunteer_id'])) {
            $query->where('volunteer_id', $filters['volunteer_id']);
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


    public function getOpportunitiesWithCache()
    {
        $cacheKey = 'opportunities_list_' . auth()->id();
        
        return Cache::remember($cacheKey, 3600, function () { 
            return Opportunity::where('status', 'active')
                ->with('organization')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        });
    }

    
    public function getDashboardStats()
    {
        $cacheKey = 'dashboard_stats_' . date('Y-m-d');
        
        return Cache::remember($cacheKey, 86400, function () { 
            return [
                'total_applications' => Application::count(),
                'pending_applications' => Application::where('status', 'pending')->count(),
                'active_tasks' => Task::where('status', 'active')->count(),
                'completed_tasks_this_month' => Task::where('status', 'complete')
                    ->whereMonth('updated_at', now()->month)
                    ->count(),
            ];
        });
    }


    public function getSkillsWithCache()
    {
        return Cache::remember('skills_list', 7200, function () { 
            return Skill::with('category')->orderBy('name')->get();
        });
    }
}