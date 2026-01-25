<?php

namespace Modules\Applications\Http\Controllers\ApplicationController;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\Application;
use Modules\Applications\Services\ApplicationService\ApplicationService;
use Modules\Applications\Http\Requests\ApplicationRequest\StoreApplicationRequest;
use Modules\Applications\Http\Requests\ApplicationRequest\UpdateApplicationRequest;

class ApplicationController extends Controller
{
    public function __construct(private ApplicationService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexApplicationRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Application::class);
        $applications = $this->service->getAllApplications($request->validated());
        return  response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $this->authorize('create', Application::class);
        $application = $this->service->createApplication($request->validated());
        return response()->json($application, 201);
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $application = $this->service->getApplication($id);
        $this->authorize('view', $application);
        return response()->json($application);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplicationRequest $request, int $id): JsonResponse
    {
        $application = $this->service->getApplication($id);
        $this->authorize('update', $application);
        $application = $this->service->updateApplication($id, $request->validated());
        return response()->json($application);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $application = $this->service->getApplication($id);
        $this->authorize('delete', $application);
        $this->service->deleteApplication($id);
        return response()->json(null, 204);
    }

    public function updateStatus(UpdateApplicationRequest $request, int $id): JsonResponse
    {
        $application = $this->service->getApplication($id);
        $this->authorize('update', $application);
        $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $application = $this->service->updateApplicationStatus($id, $request);
        return response()->json($application);
    } 
}
