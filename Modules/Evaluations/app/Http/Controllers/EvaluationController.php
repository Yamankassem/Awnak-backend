<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\EvaluationResource;
use Illuminate\Http\Request;
use Modules\Evaluations\Services\EvaluationService;
use App\Http\Traits\ApiResponse;
use Modules\Evaluations\Http\Requests\StoreEvaluationRequest;
use Modules\Evaluations\Http\Requests\UpdateEvaluationRequest;
use Modules\Evaluations\Models\Evaluation;

class EvaluationController extends Controller
{
    use ApiResponse;

    protected $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    // Display all volunteer's evaluations
    public function index($volunteerId)
    {
        $evaluations = $this->evaluationService->getByVolunteer($volunteerId);
        return $this->successResponse(
            EvaluationResource::collection($evaluations),
            'Evaluations retrieved successfully',
            200
        );
    }
    
    // Show evaluation
    public function show($id)
    {
        try {
            $evaluation = $this->evaluationService->getCategoryById($id);
            return $this->successResponse(
                new EvaluationResource($evaluation),
                'Evaluation retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Evaluation not found', 404);
        }
    }

     // create new evaluation
    public function store(StoreEvaluationRequest $request)
    {
        try {
            $evaluation = $this->evaluationService->createEvaluation($request->validated());
            return $this->successResponse(
                new EvaluationResource($evaluation),
                'Evaluation created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

     // Update  new evaluation
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        try {
            $updated = $this->evaluationService->updateEvaluation($request->validated(), $evaluation);
            return $this->successResponse(
                new EvaluationResource($updated),
                'Evaluation updated successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
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