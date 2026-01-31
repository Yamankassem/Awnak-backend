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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class EvaluationController extends Controller
{
     use AuthorizesRequests;

    protected $evaluationService;

    public function __construct(EvaluationServices $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    // Display all evaluations
    public function index()
    {
        try {
             
            $this->authorize('viewAny', Evaluation::class);
            $evaluations = $this->evaluationService->getAllEvaluations();
            return static::paginated(
            paginator: $evaluations,
            message: 'evaluations.listed'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    // Show single evaluation
    public function show($id)
    {
        try {
            $evaluation = $this->evaluationService->getEvaluationById($id);
             $this->authorize('view', $evaluation);
             return static::success(
                data: $evaluation,
                message: 'evaluations.retrieved',
                status: 201
        );
        } catch (\Exception $e) {
            return $this->error('Evaluation not found', 404);
        }
    }
    // Create evaluation
    public function store(StoreEvaluationRequest $request)
    {
        try {
                    $this->authorize('create', Evaluation::class);
                    $data = $request->validated();
                    $data['evaluator_id'] = Auth::id();

                $evaluation = $this->evaluationService->createEvaluation($data);

                return static::success(
                                        data:  $evaluation,
                                        message: 'evaluations.created',
                                        status: 201
                                    );
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode() ?: 500);
            }
        
    }

    // Update evaluation
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        try {
                $this->authorize('update', $evaluation);
                $updated = $this->evaluationService->updateEvaluation($evaluation, $request->validated());
                  return static::success(
                        data: $updated,
                        message: 'evaluations.updated'
                    );
            } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
            }
    }

    // Delete evaluation
    public function destroy(Evaluation $evaluation)
    {
        try {
            $this->authorize('delete', $evaluation);
            $this->evaluationService->deleteEvaluation($evaluation);
            return static::success(
                data: null,
                message: 'evaluations.deleted',
                status: 200
            );
        } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
