<?php

namespace Modules\Applications\Http\Controllers\FeedbackController;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Http\Requests\FeedbackRequest\StoreFeedbackRequest;
use Modules\Applications\Http\Requests\FeedbackRequest\UpdateFeedbackRequest;

class FeedbackController extends Controller
{
    public function __construct(private FeedbackService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $feedbacks = $this->service->getAllFeedbacks($request->validated());
        return  response()->json($feedbacks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $feedback = $this->service->createFeedback($request->validated());
        return response()->json($feedback, 201);
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $feedback = $this->service->getFeedback($id);
        return response()->json($feedback);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedbackRequest $request, int $id): JsonResponse
    {
        $feedback = $this->service->updateFeedback($id, $request->validated());
        return response()->json($feedback);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->deleteFeedback($id);
        return response()->json(null, 204);
    }

    public function updateStatus(UpdateFeedbackRequest $request, int $id): JsonResponse
    {
        $request->validated();
        $feedback = $this->service->updateFeedbackStatus($id, $request->status);
        return response()->json($feedback);
    }
}
