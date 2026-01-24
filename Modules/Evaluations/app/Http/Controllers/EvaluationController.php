<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Evaluations\Http\Resources\EvaluationResource;
use Modules\Evaluations\Services\EvaluationService;
use App\Http\Traits\ApiResponse;
use Modules\Evaluations\Http\Requests\StoreEvaluationRequest;
use Modules\Evaluations\Http\Requests\UpdateEvaluationRequest;
use Modules\Evaluations\Models\Evaluation;

class EvaluationController extends Controller
{
    use ApiResponse;

    protected EvaluationService $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    // Display all volunteer evaluations
    public function index($volunteerId)
    {
        $evaluations = $this->evaluationService->getByVolunteer($volunteerId);

        return $this->successResponse(
            EvaluationResource::collection($evaluations),
            'Evaluations retrieved successfully'
        );
    }

    // Show single evaluation
    public function show($id)
    {
        $evaluation = $this->evaluationService->find($id);

        return $this->successResponse(
            new EvaluationResource($evaluation),
            'Evaluation retrieved successfully'
        );
    }

    // Create evaluation
    public function store(StoreEvaluationRequest $request)
    {
        $data = $request->validated();
        $data['evaluator_id'] = auth()->id();

        $evaluation = $this->evaluationService->createEvaluation($data);

        return $this->successResponse(
            new EvaluationResource($evaluation),
            'Evaluation created successfully',
            201
        );
    }

    // Update evaluation
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $updated = $this->evaluationService->updateEvaluation( $evaluation,$request->validated());
        return $this->successResponse(
            new EvaluationResource($updated),
            'Evaluation updated successfully'
        );
    }

    // Delete evaluation
    public function destroy(Evaluation $evaluation)
    {
        $this->evaluationService->deleteEvaluation($evaluation);

        return $this->successResponse(
            null,
            'Evaluation deleted successfully'
        );
    }
}
