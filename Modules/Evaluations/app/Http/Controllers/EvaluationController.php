<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Evaluations\Http\Requests\Evaluation\StoreEvaluationRequest;
use Modules\Evaluations\Http\Requests\Evaluation\UpdateEvaluationRequest;
use Modules\Evaluations\Http\Resources\EvaluationResource;
use Modules\Evaluations\Http\Traits\ApiResponse;
use Modules\Evaluations\Models\Evaluation;
use Modules\Evaluations\Services\EvaluationServices;

class EvaluationController extends Controller
{
    use ApiResponse;

    protected $evaluationService;

    public function __construct(EvaluationServices $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    // Display all volunteer evaluations
    public function index($volunteerId)
    {
        try {
            $evaluations = $this->evaluationService->getByVolunteer($volunteerId);

            return $this->successResponse(
                EvaluationResource::collection($evaluations),
                'Evaluations retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    // Show single evaluation
    public function show($id)
    {
        try {
            $evaluation = $this->evaluationService->getEvaluationById($id);

            return $this->successResponse(
                new EvaluationResource($evaluation),
                'Evaluation retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Evaluation not found', 404);
        }
    }

    // Create evaluation
    public function store(StoreEvaluationRequest $request)
    {
        try {
            $data = $request->validated();
            $data['evaluator_id'] = Auth::id();

            $evaluation = $this->evaluationService->createEvaluation($data);

            return $this->successResponse(
                new EvaluationResource($evaluation),
                'Evaluation created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    // Update evaluation
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        try {
            $updated = $this->evaluationService
                ->updateEvaluation($evaluation, $request->validated());

            return $this->successResponse(
                new EvaluationResource($updated),
                'Evaluation updated successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    // Delete evaluation
    public function destroy(Evaluation $evaluation)
    {
        try {
            $this->evaluationService->deleteEvaluation($evaluation);

            return $this->successResponse(
                null,
                'Evaluation deleted successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }
}
