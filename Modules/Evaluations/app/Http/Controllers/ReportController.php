<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Evaluations\Http\Requests\Report\StoreReportRequest;
use Modules\Evaluations\Http\Resources\ReportResource;
use Modules\Evaluations\Http\Traits\ApiResponse;
use Modules\Evaluations\Models\Report;
use Modules\Evaluations\Services\ReportService;

class ReportController extends Controller
{
    use ApiResponse;

    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function show($id)
    {
        try {
            $report = $this->reportService->getById($id);

            return $this->successResponse(
                new ReportResource($report),
                'Report retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Report not found', 404);
        }
    }

    public function store(StoreReportRequest $request)
    {
        try {
            $report = $this->reportService->createReport($request->validated());

            return $this->successResponse(
                new ReportResource($report),
                'Report generated successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function destroy(Report $report)
    {
        try {
            $this->reportService->deleteReport($report);

            return $this->successResponse(
                null,
                'Report deleted successfully',
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
